<?php

namespace App\Http\Controllers\Run;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\CustomsDeclaration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use GeniusTS\HijriDate\Hijri;

class CustomsController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        // استخدام التخزين المؤقت للبيانات
        $users = Cache::remember('active_users_' . $query, 600, function () use ($query) {
            return User::where('role', 'client')
                ->where('is_active', 1)
                ->select('id', 'name', 'created_at')
                ->with(['container', 'customs'])
                ->when($query, function ($q) use ($query) {
                    $q->where('created_at', 'like', '%' . $query . '%')
                        ->orWhere('name', 'like', '%' . $query . '%');
                })
                ->paginate(10); // استخدام التقسيم (Pagination)
        });

        $usersDeleted = Cache::remember('inactive_users', 600, function () {
            return User::where('role', 'client')
                ->where('is_active', 0)
                ->select('id', 'name', 'created_at')
                ->with(['container', 'customs'])
                ->paginate(10); // استخدام التقسيم (Pagination)
        });

        return view('run.Customs', compact('users', 'usersDeleted'));
    }
    public function getOfficeContainerData($clientId)
    {
        $users = User::find($clientId);
        return view('run.officeContanierData', compact('users'));
    }
    public function showContainerPost($customId)
    {
        $custom = CustomsDeclaration::find($customId);
        return view('run.addContanier', compact('custom'));
    }

    public function store(Request $request, $clientId)
    {
        $request->validate([
            'statement_number' => 'required',
            'subClient' => 'required',
            'customs_weight' => 'required',
            'expire_customs' => 'required',
            'created_at' => 'nullable',
        ]);

        // التحقق من صحة التاريخ الهجري
        $hijriDate = $request->expire_customs;
        try {
            // تقسيم التاريخ الهجري إلى أجزاء (سنة، شهر، يوم)
            list($hijriYear, $hijriMonth, $hijriDay) = explode('-', $hijriDate);

            // تحويل التاريخ الهجري إلى ميلادي
            $gregorianDate = Hijri::convertToGregorian($hijriYear, $hijriMonth, $hijriDay);

            // تنسيق التاريخ الميلادي كـ YYYY-MM-DD
            $gregorianDateFormatted = $gregorianDate->format('Y-m-d');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'الرجاء إدخال تاريخ هجري صحيح (YYYY/MM/DD)');
        }

        $customNumis = CustomsDeclaration::where('statement_number', $request->statement_number)->first();
        if ($customNumis && !$customNumis->container()->exists()) {
            $customNumis->delete();
        }

        $existingDeclaration = CustomsDeclaration::where('statement_number', $request->statement_number)
            ->whereYear('created_at', now()->year)
            ->first();
        if ($existingDeclaration) {
            return redirect()->back()->with('error', 'رقم البيان موجود بالفعل لهذا العام.');
        }

        $data = CustomsDeclaration::create([
            'statement_number' => $request->statement_number,
            'subclient_id' => $request->subClient,
            'client_id' => $clientId,
            'customs_weight' => $request->customs_weight,
            'expire_customs' => $gregorianDateFormatted, // حفظ التاريخ الميلادي
            'created_at' => $request->created_at,
        ]);

        return redirect()->route('showContanierPost', [
            'contNum' => $request->contNum,
            'customId' => $data->id,
        ])->with('success', 'تم انشاء بيان بنجاح');
    }

    public function deleteOffices($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
        if ($user->role === 'client') {
            $user->update(['is_active' => $user->is_active ? 0 : 1]);
            return redirect()->back()->with('success', 'User deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'You do not have permission to delete this user.');
        }
    }

    public function showContainer($customId)
    {
        $custom = CustomsDeclaration::findOrFail($customId);
        return view('FinancialManagement.Revenues.custom-with-container', compact('custom'));
    }

}
