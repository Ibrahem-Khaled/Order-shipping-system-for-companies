<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role == 'superAdmin') {
            return $next($request);
        }
        if (auth()->check() && auth()->user()->role == 'driver') {
            $allowedRoutes = [
                'expensesSallaryeEmployee',
                'expensesEmployeeDaily',
                'expensesEmployeeTips',
            ];
            if (!in_array($request->route()->getName(), $allowedRoutes)) {
                return redirect()->route('expensesSallaryeEmployee');
            }
            return $next($request);
        }
        if (auth()->check() && auth()->user()?->userinfo?->job_title == 'operator') {
            $allowedRoutes = [
                'addOffice',
                'postOffice',
                'getOfices',
                'postCustoms',
                'showContanierPost',
                'addContainer',
                'editContainerPrice',
                'empty',
                'updateContainer',
                'updateEmpty',
                'dates',
                'ContainerRentStatus',
            ];
            if (!in_array($request->route()->getName(), $allowedRoutes)) {
                return redirect()->route('getOfices');
            }
            return $next($request);
        }
        if (auth()->check() && auth()->user()?->userinfo?->job_title == 'administrative') {
            $allowedRoutes = [
                'dailyManagement',
                'editContanierTips',
                'addContanierPriceTransfer',
                'addOtherStateMent',
                'postDailyData',
                'expensesCarsData',
                'expensesCarDaily',
                'expensesSallaryAlbancher',
                'expensesAlbancherDaily',
                'expensesSallaryeEmployee',
                'expensesEmployeeDaily',
                'expensesEmployeeTips',
                'expensesOthers',
                'FinancialManagement',
                'sell.buy',
                'transactions.store',
                'getRevenuesClient',
                'getAccountStatement',
                'getAccountYears',
                'getOfficesRent',
                'getrentMonth',
            ];
            if (!in_array($request->route()->getName(), $allowedRoutes)) {
                return redirect()->route('dailyManagement');
            }
            return $next($request);
        }
        return redirect()->route('home');
    }
}
