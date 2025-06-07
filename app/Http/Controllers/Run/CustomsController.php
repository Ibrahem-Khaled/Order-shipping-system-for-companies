<?php

namespace App\Http\Controllers\Run;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\CustomsDeclaration;
use App\Models\FlatbedContainer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomsController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $office = $request->input('office');
        // جلب المستخدمين النشطين وغير النشطين دفعة واحدة

        $users = User::where('role', 'client')
            ->when($office, function ($q) use ($office) {
                $q->where('name', 'like', '%' . $office . '%');
            })
            ->withCount([
                // عدّ الحاويات بحالة 'wait' أو 'rent'
                'container as active_containers_count' => function ($q) {
                    $q->whereIn('status', ['wait', 'rent']);
                },
                // عدّ البيانات المرتبطة بحاويات في الحالة نفسها
                'customs as active_customs_count' => function ($q) {
                    $q->whereHas('container', function ($q2) {
                        $q2->whereIn('status', ['wait', 'rent']);
                    });
                },
            ])
            ->orderBy('is_active', 'desc')
            ->paginate(15);


        // تقسيم المستخدمين بناءً على الحالة
        $usersActive = $users->where('is_active', 1);
        $usersDeleted = $users->where('is_active', 0);

        $totalActiveClients = User::where('role', 'client')->where('is_active', 1)->count();
        $totalDeletedClients = User::where('role', 'client')->where('is_active', 0)->count();
        $totalActiveContainers = Container::whereIn('status', ['wait', 'rent'])->count();
        $totalActiveCustoms = CustomsDeclaration::count();

        $getLastClintsFromthreeDays = User::where('role', 'client')->where('is_active', 1)
            ->whereDate('created_at', '>=', now()->subDays(3))->get();

        return view('run.Customs', compact(
            'usersActive',
            'usersDeleted',
            'users',
            'getLastClintsFromthreeDays',
            'totalActiveClients',
            'totalDeletedClients',
            'totalActiveContainers',
            'totalActiveCustoms'
        ));
    }

    public function getOfficeContainerData($clientId)
    {
        $users = User::find($clientId);
        return view('run.officeContanierData', compact('users'));
    }

    public function deleteContainer(Container $container)
    {
        // تحقق مما إذا كان الحاوية مرتبطة ببيان جمركي
        // if ($container->customs()->exists()) {
        //     return redirect()->back()->with('error', 'لا يمكن حذف الحاوية لأنها مرتبطة ببيان جمركي.');
        // }

        // حذف الحاوية
        FlatbedContainer::where('container_id', $container->id)->delete();
        $container->delete();

        return redirect()->back()->with('success', 'تم حذف الحاوية بنجاح يا مهرهر');
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
            'expire_customs' => 'nullable|date',
            'created_at' => 'nullable',
        ]);

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
            'importer_name' => $request->subClient,
            'client_id' => $clientId,
            'customs_weight' => $request->customs_weight,
            'expire_customs' => $request->expire_customs,
            'created_at' => $request->created_at,
        ]);

        return redirect()->route('showContanierPost', [
            'contNum' => $request->contNum,
            'customId' => $data->id,
        ])->with('success', 'تم انشاء بيان بنجاح');
    }

    public function updateDateCustom(Request $request, $id)
    {
        $request->validate([
            'date_custom' => 'required|date',
        ]);

        $record = CustomsDeclaration::findOrFail($id);
        // نفترض أن الحقل المعدل في قاعدة البيانات يحمل نفس الاسم أو قم بتغيير الاسم حسب الحاجة
        $record->expire_customs = $request->input('date_custom');
        $record->save();

        return redirect()->back()->with('success', 'تم تحديث تاريخ أرضية الجمرك بنجاح.');
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
