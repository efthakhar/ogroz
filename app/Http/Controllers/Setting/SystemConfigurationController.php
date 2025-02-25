<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class SystemConfigurationController extends Controller
{
    use AuthorizesRequests;
    /**
     * Return system configurations
     */
    public function systemConfigurations()
    {
        $this->authorize('manage system configurations');
        $systemConfigurations = getOption('system_configurations');
        return view('setting.system-configuration.configurations', compact('systemConfigurations'));
    }

    /**
     * Handle submitted configurations data 
     */
    public function systemConfigurationsSubmit(Request $request)
    {
        $this->authorize('manage system configurations');
        
        $validatedData = $request->validate([
            'company_name' => 'required',
            'company_email' => 'required',
            'company_phone_no' => 'required',
            'company_address' => 'required',
        ]);

        try {
            $data = [];
            $data['company_name'] = $validatedData['company_name'];
            $data['company_email'] = $validatedData['company_email'];
            $data['company_phone_no'] = $validatedData['company_phone_no'];
            $data['company_address'] = $validatedData['company_address'];
            setOption('system_configurations', $data, []);

            return redirect()->back()->with('success', "Data submitted successfully");
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
