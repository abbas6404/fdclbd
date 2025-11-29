<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeadOfAccount;

class ChartOfAccountsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing accounts
        HeadOfAccount::query()->delete();

        // Create Operating Income structure
        $operatingIncome = HeadOfAccount::create([
            'account_name' => 'Operating Income',
            'account_type' => 'income',
            'parent_id' => null,
            'account_level' => '1',
            'status' => 'active',
            'created_by' => 1,
        ]);

        // Real Estate Sales Income
        $salesIncome = HeadOfAccount::create([
            'account_name' => 'Real Estate Sales Income',
            'account_type' => 'income',
            'parent_id' => $operatingIncome->id,
            'account_level' => '2',
            'status' => 'active',
            'created_by' => '1',
        ]);

        $salesIncomeDetail = HeadOfAccount::create([
            'account_name' => 'Flat Sales Income',
            'account_type' => 'income',
            'parent_id' => $salesIncome->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        // Insert child accounts for income
        HeadOfAccount::create([
            'account_name' => 'Flat Sales Revenue',
            'account_type' => 'income',
            'parent_id' => $salesIncomeDetail->id,
            'account_level' => '4',
            'status' => 'active',
            'created_by' => 1,
        ]);

        HeadOfAccount::create([
            'account_name' => 'Booking Amount Income',
            'account_type' => 'income',
            'parent_id' => $salesIncomeDetail->id,
            'account_level' => '4',
            'status' => 'active',
            'created_by' => 1,
        ]);

        HeadOfAccount::create([
            'account_name' => 'Installment Income',
            'account_type' => 'income',
            'parent_id' => $salesIncomeDetail->id,
            'account_level' => '4',
            'status' => 'active',
            'created_by' => 1,
        ]);

        // Other Income
        $otherIncome = HeadOfAccount::create([
            'account_name' => 'Other Income',
            'account_type' => 'income',
            'parent_id' => $operatingIncome->id,
            'account_level' => '2',
            'status' => 'active',
            'created_by' => 1,
        ]);

        HeadOfAccount::create([
            'account_name' => 'Interest Income',
            'account_type' => 'income',
            'parent_id' => $otherIncome->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        HeadOfAccount::create([
            'account_name' => 'Miscellaneous Income',
            'account_type' => 'income',
            'parent_id' => $otherIncome->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        // Create Operating Expense structure
        $operatingExpense = HeadOfAccount::create([
            'account_name' => 'OPERATING EXPENSES',
            'account_type' => 'expense',
            'parent_id' => null,
            'account_level' => '1',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $adminExpense = HeadOfAccount::create([
            'account_name' => 'Administrative Expenses',
            'account_type' => 'expense',
            'parent_id' => $operatingExpense->id,
            'account_level' => '2',
            'status' => 'active',
            'created_by' => 1,
        ]);

        // Add all the additional expense accounts for real estate/construction
        // Create Level 3 accounts first
        $directorHonorarium = HeadOfAccount::create([
            'account_name' => 'Director Honorarium',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $salary = HeadOfAccount::create([
            'account_name' => 'Salary',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $festivalBonus = HeadOfAccount::create([
            'account_name' => 'Festival Bonus',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $marketingConveyance = HeadOfAccount::create([
            'account_name' => 'Marketing Conveyance',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $officeConveyance = HeadOfAccount::create([
            'account_name' => 'Office Conveyance',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $advertisementBill = HeadOfAccount::create([
            'account_name' => 'Advertisement Bill',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $newspaperBill = HeadOfAccount::create([
            'account_name' => 'Newspaper Bill',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $dishLineBill = HeadOfAccount::create([
            'account_name' => 'Dish Line Bill',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $mobileBill = HeadOfAccount::create([
            'account_name' => 'Mobile Bill',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $electricityBill = HeadOfAccount::create([
            'account_name' => 'Electricity Bill',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $wasteManagementBill = HeadOfAccount::create([
            'account_name' => 'Waste Management Bill',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $internetBill = HeadOfAccount::create([
            'account_name' => 'Internet Bill',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $unofficialOfficeExpenses = HeadOfAccount::create([
            'account_name' => 'Unofficial Office Expenses',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $officeEntertainmentExpenses = HeadOfAccount::create([
            'account_name' => 'Office Entertainment Expenses',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $websiteDevelopmentExp = HeadOfAccount::create([
            'account_name' => 'Website Development Exp',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $digitalMarketingBill = HeadOfAccount::create([
            'account_name' => 'Digital Marketing Bill',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $softwareServiceCharge = HeadOfAccount::create([
            'account_name' => 'Software Service Charge',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $computerAndITExpense = HeadOfAccount::create([
            'account_name' => 'Computer and IT Expense',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $buildingRent = HeadOfAccount::create([
            'account_name' => 'Building Rent',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $repairAndMaintenanceExpenses = HeadOfAccount::create([
            'account_name' => 'Repair and Maintenance Expenses',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $washingAndCleaningExpense = HeadOfAccount::create([
            'account_name' => 'Washing and Cleaning Expense',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $officeStationary = HeadOfAccount::create([
            'account_name' => 'Office Stationary',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $printingAndPackaging = HeadOfAccount::create([
            'account_name' => 'Printing And Packaging',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $returnSalesReturn = HeadOfAccount::create([
            'account_name' => 'Return/Sales Return',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $courierServiceCharge = HeadOfAccount::create([
            'account_name' => 'Courier Service Charge',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $fuelCNG = HeadOfAccount::create([
            'account_name' => 'Fuel/CNG',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $constructionMaterials = HeadOfAccount::create([
            'account_name' => 'Construction Materials',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $laborCosts = HeadOfAccount::create([
            'account_name' => 'Labor Costs',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $contractorPayments = HeadOfAccount::create([
            'account_name' => 'Contractor Payments',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $legalCompliance = HeadOfAccount::create([
            'account_name' => 'Legal & Compliance',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $commissionIncentives = HeadOfAccount::create([
            'account_name' => 'Commission & Incentives',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        $newInvestment = HeadOfAccount::create([
            'account_name' => 'New Investment',
            'account_type' => 'expense',
            'parent_id' => $adminExpense->id,
            'account_level' => '3',
            'status' => 'active',
            'created_by' => 1,
        ]);

        // Now create Level 4 accounts with proper Level 3 parents
        $accounts = [
            // Director Honorarium - Level 4
            ['account_name' => 'Director Honorarium', 'parent_id' => $directorHonorarium->id, 'account_level' => '4'],
            
            // Salary - Level 4
            ['account_name' => 'Staff Salary', 'parent_id' => $salary->id, 'account_level' => '4'],
            ['account_name' => 'Staff Salary (Advance)', 'parent_id' => $salary->id, 'account_level' => '4'],
            ['account_name' => 'Manager Salary', 'parent_id' => $salary->id, 'account_level' => '4'],
            ['account_name' => 'Sales Agent Salary', 'parent_id' => $salary->id, 'account_level' => '4'],
            ['account_name' => 'Project Manager Salary', 'parent_id' => $salary->id, 'account_level' => '4'],
            ['account_name' => 'Accountant Salary', 'parent_id' => $salary->id, 'account_level' => '4'],
            
            // Festival Bonus - Level 4
            ['account_name' => 'Bonus For Eid ul Fitar', 'parent_id' => $festivalBonus->id, 'account_level' => '4'],
            ['account_name' => 'Bonus For Eid ul Azha', 'parent_id' => $festivalBonus->id, 'account_level' => '4'],
            ['account_name' => 'Bonus For Special Purpose', 'parent_id' => $festivalBonus->id, 'account_level' => '4'],
            
            // Marketing Conveyance - Level 4
            ['account_name' => 'Marketing Conveyance For Staff', 'parent_id' => $marketingConveyance->id, 'account_level' => '4'],
            ['account_name' => 'Marketing Conveyance For Auto', 'parent_id' => $marketingConveyance->id, 'account_level' => '4'],
            ['account_name' => 'Marketing Conveyance For CNG', 'parent_id' => $marketingConveyance->id, 'account_level' => '4'],
            ['account_name' => 'Marketing Conveyance For Motorcycle', 'parent_id' => $marketingConveyance->id, 'account_level' => '4'],
            ['account_name' => 'Sales Agent Conveyance', 'parent_id' => $marketingConveyance->id, 'account_level' => '4'],
            
            // Office Conveyance - Level 4
            ['account_name' => 'Office Conveyance for Staff', 'parent_id' => $officeConveyance->id, 'account_level' => '4'],
            ['account_name' => 'Office Conveyance for Directors', 'parent_id' => $officeConveyance->id, 'account_level' => '4'],
            ['account_name' => 'Office Conveyance for Managers', 'parent_id' => $officeConveyance->id, 'account_level' => '4'],
            
            // Advertisement Bill - Level 4
            ['account_name' => 'Dish Line Advertisement Bill', 'parent_id' => $advertisementBill->id, 'account_level' => '4'],
            ['account_name' => 'Local Newspaper', 'parent_id' => $advertisementBill->id, 'account_level' => '4'],
            ['account_name' => 'Inside Newspaper', 'parent_id' => $advertisementBill->id, 'account_level' => '4'],
            ['account_name' => 'Banner / Festoon', 'parent_id' => $advertisementBill->id, 'account_level' => '4'],
            ['account_name' => 'Visiting Card', 'parent_id' => $advertisementBill->id, 'account_level' => '4'],
            ['account_name' => 'Project Billboard', 'parent_id' => $advertisementBill->id, 'account_level' => '4'],
            
            // Newspaper Bill - Level 4
            ['account_name' => 'Newspaper Bill', 'parent_id' => $newspaperBill->id, 'account_level' => '4'],
            
            // Dish Line Bill - Level 4
            ['account_name' => 'Dish Line Monthly Bill', 'parent_id' => $dishLineBill->id, 'account_level' => '4'],
            
            // Mobile Bill - Level 4
            ['account_name' => 'Mobile Bill For Office', 'parent_id' => $mobileBill->id, 'account_level' => '4'],
            ['account_name' => 'Mobile Bill For Marketing Staff', 'parent_id' => $mobileBill->id, 'account_level' => '4'],
            ['account_name' => 'Mobile Bill For Sales Agents', 'parent_id' => $mobileBill->id, 'account_level' => '4'],
            
            // Electricity Bill - Level 4
            ['account_name' => 'Office Electricity Bill', 'parent_id' => $electricityBill->id, 'account_level' => '4'],
            ['account_name' => 'Project Site Electricity Bill', 'parent_id' => $electricityBill->id, 'account_level' => '4'],
            
            // Waste Management Bill - Level 4
            ['account_name' => 'Waste Management Bill', 'parent_id' => $wasteManagementBill->id, 'account_level' => '4'],
            
            // Internet Bill - Level 4
            ['account_name' => 'Internet Bill', 'parent_id' => $internetBill->id, 'account_level' => '4'],
            
            // Unofficial Office Expenses - Level 4
            ['account_name' => 'Unofficial Office Expenses', 'parent_id' => $unofficialOfficeExpenses->id, 'account_level' => '4'],
            
            // Office Entertainment Expenses - Level 4
            ['account_name' => 'Entertainment Exp. For Staff', 'parent_id' => $officeEntertainmentExpenses->id, 'account_level' => '4'],
            ['account_name' => 'Entertainment Exp. for Guest', 'parent_id' => $officeEntertainmentExpenses->id, 'account_level' => '4'],
            ['account_name' => 'Entertainment Exp. for Clients', 'parent_id' => $officeEntertainmentExpenses->id, 'account_level' => '4'],
            
            // Website Development Exp - Level 4
            ['account_name' => 'Website Domain Exp', 'parent_id' => $websiteDevelopmentExp->id, 'account_level' => '4'],
            
            // Digital Marketing Bill - Level 4
            ['account_name' => 'Digital Marketing For Social Media', 'parent_id' => $digitalMarketingBill->id, 'account_level' => '4'],
            ['account_name' => 'Digital Marketing For SMS', 'parent_id' => $digitalMarketingBill->id, 'account_level' => '4'],
            
            // Software Service Charge - Level 4
            ['account_name' => 'Software Service Charge', 'parent_id' => $softwareServiceCharge->id, 'account_level' => '4'],
            
            // Computer and IT Expense - Level 4
            ['account_name' => 'Computer and IT Expense', 'parent_id' => $computerAndITExpense->id, 'account_level' => '4'],
            
            // Building Rent - Level 4
            ['account_name' => 'Office Building Rent', 'parent_id' => $buildingRent->id, 'account_level' => '4'],
            ['account_name' => 'Showroom Rent', 'parent_id' => $buildingRent->id, 'account_level' => '4'],
            
            // Repair and Maintenance Expenses - Level 4
            ['account_name' => 'R/M Electrical Item', 'parent_id' => $repairAndMaintenanceExpenses->id, 'account_level' => '4'],
            ['account_name' => 'R/M Interior and Decoration', 'parent_id' => $repairAndMaintenanceExpenses->id, 'account_level' => '4'],
            ['account_name' => 'R/M Furniture', 'parent_id' => $repairAndMaintenanceExpenses->id, 'account_level' => '4'],
            ['account_name' => 'R/M AC', 'parent_id' => $repairAndMaintenanceExpenses->id, 'account_level' => '4'],
            ['account_name' => 'R/M Office Equipment', 'parent_id' => $repairAndMaintenanceExpenses->id, 'account_level' => '4'],
            ['account_name' => 'R/M Generator', 'parent_id' => $repairAndMaintenanceExpenses->id, 'account_level' => '4'],
            ['account_name' => 'R/M Sign Board/Banner/Festoon', 'parent_id' => $repairAndMaintenanceExpenses->id, 'account_level' => '4'],
            ['account_name' => 'R/M Computer & IT Products', 'parent_id' => $repairAndMaintenanceExpenses->id, 'account_level' => '4'],
            ['account_name' => 'R/M Lift', 'parent_id' => $repairAndMaintenanceExpenses->id, 'account_level' => '4'],
            ['account_name' => 'R/M Construction Equipment', 'parent_id' => $repairAndMaintenanceExpenses->id, 'account_level' => '4'],
            
            // Washing and Cleaning Expense - Level 4
            ['account_name' => 'Washing And Cleaning (All Type)', 'parent_id' => $washingAndCleaningExpense->id, 'account_level' => '4'],
            
            // Office Stationary - Level 4
            ['account_name' => 'Office Stationary (All)', 'parent_id' => $officeStationary->id, 'account_level' => '4'],
            
            // Printing And Packaging - Level 4
            ['account_name' => 'Printing and Packaging Exp', 'parent_id' => $printingAndPackaging->id, 'account_level' => '4'],
            
            // Return/Sales Return - Level 4
            ['account_name' => 'Return Bill', 'parent_id' => $returnSalesReturn->id, 'account_level' => '4'],
            
            // Courier Service Charge - Level 4
            ['account_name' => 'Courier Service Charge', 'parent_id' => $courierServiceCharge->id, 'account_level' => '4'],
            
            // Fuel/CNG - Level 4
            ['account_name' => 'Fuel For Generator', 'parent_id' => $fuelCNG->id, 'account_level' => '4'],
            ['account_name' => 'Fuel For Office Vehicle', 'parent_id' => $fuelCNG->id, 'account_level' => '4'],
            ['account_name' => 'Fuel For Motorcycle', 'parent_id' => $fuelCNG->id, 'account_level' => '4'],
            
            // Construction Materials - Level 4
            ['account_name' => 'Construction Materials', 'parent_id' => $constructionMaterials->id, 'account_level' => '4'],
            ['account_name' => 'Cement', 'parent_id' => $constructionMaterials->id, 'account_level' => '4'],
            ['account_name' => 'Steel/Rod', 'parent_id' => $constructionMaterials->id, 'account_level' => '4'],
            ['account_name' => 'Brick', 'parent_id' => $constructionMaterials->id, 'account_level' => '4'],
            ['account_name' => 'Sand', 'parent_id' => $constructionMaterials->id, 'account_level' => '4'],
            ['account_name' => 'Stone/Crushed Stone', 'parent_id' => $constructionMaterials->id, 'account_level' => '4'],
            ['account_name' => 'Tiles', 'parent_id' => $constructionMaterials->id, 'account_level' => '4'],
            ['account_name' => 'Paint', 'parent_id' => $constructionMaterials->id, 'account_level' => '4'],
            ['account_name' => 'Electrical Materials', 'parent_id' => $constructionMaterials->id, 'account_level' => '4'],
            ['account_name' => 'Plumbing Materials', 'parent_id' => $constructionMaterials->id, 'account_level' => '4'],
            ['account_name' => 'Sanitary Materials', 'parent_id' => $constructionMaterials->id, 'account_level' => '4'],
            
            // Labor Costs - Level 4
            ['account_name' => 'Construction Labor', 'parent_id' => $laborCosts->id, 'account_level' => '4'],
            ['account_name' => 'Skilled Labor', 'parent_id' => $laborCosts->id, 'account_level' => '4'],
            ['account_name' => 'Unskilled Labor', 'parent_id' => $laborCosts->id, 'account_level' => '4'],
            
            // Contractor Payments - Level 4
            ['account_name' => 'Contractor Payment', 'parent_id' => $contractorPayments->id, 'account_level' => '4'],
            ['account_name' => 'Sub-Contractor Payment', 'parent_id' => $contractorPayments->id, 'account_level' => '4'],
            
            // Legal & Compliance - Level 4
            ['account_name' => 'Legal Fees', 'parent_id' => $legalCompliance->id, 'account_level' => '4'],
            ['account_name' => 'Registration Fees', 'parent_id' => $legalCompliance->id, 'account_level' => '4'],
            ['account_name' => 'Trade License Fee', 'parent_id' => $legalCompliance->id, 'account_level' => '4'],
            ['account_name' => 'Fire Service Fee', 'parent_id' => $legalCompliance->id, 'account_level' => '4'],
            ['account_name' => 'Environment Dept Fee', 'parent_id' => $legalCompliance->id, 'account_level' => '4'],
            ['account_name' => 'TIN', 'parent_id' => $legalCompliance->id, 'account_level' => '4'],
            ['account_name' => 'BIN', 'parent_id' => $legalCompliance->id, 'account_level' => '4'],
            ['account_name' => 'Income Tax & Audit Fee', 'parent_id' => $legalCompliance->id, 'account_level' => '4'],
            
            // Commission & Incentives - Level 4
            ['account_name' => 'Sales Agent Commission', 'parent_id' => $commissionIncentives->id, 'account_level' => '4'],
            ['account_name' => 'Sales Incentive', 'parent_id' => $commissionIncentives->id, 'account_level' => '4'],
            
            // New Investment - Level 4
            ['account_name' => 'New Investment', 'parent_id' => $newInvestment->id, 'account_level' => '4'],
        ];

        // Insert all additional accounts
        foreach ($accounts as $account) {
            HeadOfAccount::create([
                'account_name' => $account['account_name'],
                'account_type' => 'expense',
                'parent_id' => $account['parent_id'],
                'account_level' => $account['account_level'],
                'status' => 'active',
                'created_by' => 1,
            ]);
        }

        // Recalculate and fix all account levels based on actual hierarchy
        $this->recalculateAccountLevels();

        $this->command->info('Head of Accounts seeded successfully!');
        $this->command->info('Total accounts created: ' . HeadOfAccount::count());
        
        // Display summary
        $incomeCount = HeadOfAccount::where('account_type', 'income')->count();
        $expenseCount = HeadOfAccount::where('account_type', 'expense')->count();
        
        $this->command->info("Income accounts: {$incomeCount}");
        $this->command->info("Expense accounts: {$expenseCount}");
    }

    /**
     * Recalculate account levels based on actual parent hierarchy
     */
    private function recalculateAccountLevels()
    {
        // Get all root accounts (no parent)
        $rootAccounts = HeadOfAccount::whereNull('parent_id')->get();
        
        foreach ($rootAccounts as $rootAccount) {
            $this->updateAccountLevel($rootAccount, 1);
        }
    }

    /**
     * Recursively update account level and its children
     */
    private function updateAccountLevel($account, $level)
    {
        // Update current account level
        $account->update(['account_level' => (string)$level]);
        
        // Update children
        $children = HeadOfAccount::where('parent_id', $account->id)->get();
        foreach ($children as $child) {
            $this->updateAccountLevel($child, $level + 1);
        }
    }
}
