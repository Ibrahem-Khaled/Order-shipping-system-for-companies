<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Daily;
use App\Models\PartnerInfo;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PartnerController extends Controller
{
    public function index()
    {
        $partner = User::whereIn('role', ['partner', 'company'])
            ->get();

        $sum = 0;
        foreach ($partner as $key => $value) {
            if ($value->is_active == 1) {
                $sum += $value->partnerInfo->money;
            }
        }


        //return view('Company.partner.partner', compact('partner', 'sum', 'container', 'employee', 'daily', 'cars', 'employeeTips', 'elbancherSum', 'othersSum', 'partner'));
        return view('Company.partner.partner', compact('partner', 'sum'));
    }

    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => $request->password,
            'role' => $request->role,
        ]);
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('user_images', 'public');
        } else {
            $image = null;
        }
        UserInfo::create([
            'user_id' => $user->id,
            'number_residence' => $request->number_residence,
            'image' => $image,
        ]);
        PartnerInfo::create([
            'partner_id' => $user->id,
            'money' => $request->money,
        ]);
        return redirect()->back()->with('success', 'تم انشاء بيانات بنجاح');
    }
    public function inActive($id)
    {
        $user = User::find($id);
        $user->update([
            'is_active' => $user->is_active == 0 ? 1 : 0,
        ]);
        return redirect()->back()->with('success', 'تم تعديل بيانات بنجاح');

    }
}
