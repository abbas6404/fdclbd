<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use App\Models\Employee;
use App\Models\Project;
use App\Models\HeadOfAccount;
use Carbon\Carbon;

class RequisitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get available employees, projects, and expense accounts
        $employees = Employee::all();
        $projects = Project::all();
        $expenseAccounts = HeadOfAccount::where('account_type', 'expense')
            ->where('is_requisitions', true)
            ->get();

        if ($employees->isEmpty() || $expenseAccounts->isEmpty()) {
            $this->command->warn('No employees or expense accounts found. Please run EmployeeSeeder and ChartOfAccountsSeeder first.');
            return;
        }

        $statuses = ['pending', 'approved', 'rejected', 'completed'];
        $requisitions = [];

        // Create 20 requisitions with dummy data
        for ($i = 1; $i <= 20; $i++) {
            $requisitionDate = Carbon::now()->subDays(rand(0, 60));
            $requiredDate = $requisitionDate->copy()->addDays(rand(7, 30));
            $status = $statuses[array_rand($statuses)];
            
            $employee = $employees->random();
            $project = $projects->isNotEmpty() ? $projects->random() : null;

            $requisition = Requisition::create([
                'requisition_number' => Requisition::generateRequisitionNumber(),
                'requisition_date' => $requisitionDate,
                'required_date' => $requiredDate,
                'total_amount' => 0, // Will be calculated from items
                'status' => $status,
                'remark' => $this->getRandomRemark($status),
                'employee_id' => $employee->id,
                'project_id' => $project ? $project->id : null,
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            // Create 2-5 items for each requisition
            $itemCount = rand(2, 5);
            $units = ['pcs', 'kg', 'ltr', 'm', 'sqft'];

            for ($j = 1; $j <= $itemCount; $j++) {
                $account = $expenseAccounts->random();
                $qty = rand(1, 100);
                $unit = $units[array_rand($units)];

                RequisitionItem::create([
                    'requisition_id' => $requisition->id,
                    'head_of_account_id' => $account->id,
                    'description' => $this->getItemDescription($account->account_name),
                    'qty' => $qty,
                    'unit' => $unit,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
            }
        }

        $this->command->info('Requisitions seeded successfully!');
        $this->command->info('Total requisitions created: 20');
        $this->command->info('Total requisition items created: ' . RequisitionItem::count());
    }

    /**
     * Get random remark based on status
     */
    private function getRandomRemark($status)
    {
        $remarks = [
            'pending' => [
                'Urgent requirement for project completion',
                'Materials needed for ongoing construction',
                'Office supplies and equipment purchase',
                'Regular monthly procurement',
                'Emergency purchase request',
            ],
            'approved' => [
                'Approved for immediate procurement',
                'Budget approved, proceed with purchase',
                'Approved as per project requirements',
                'Approved for bulk purchase',
            ],
            'rejected' => [
                'Budget constraints, request denied',
                'Items not required at this time',
                'Alternative solution available',
                'Request exceeds budget allocation',
            ],
            'completed' => [
                'All items procured and delivered',
                'Purchase completed successfully',
                'Materials received and verified',
                'Requisition fulfilled',
            ],
        ];

        $statusRemarks = $remarks[$status] ?? ['No remarks'];
        return $statusRemarks[array_rand($statusRemarks)];
    }

    /**
     * Get item description based on account name
     */
    private function getItemDescription($accountName)
    {
        $descriptions = [
            'Cement' => 'Premium grade cement for construction',
            'Steel/Rod' => 'High-quality steel rods for reinforcement',
            'Brick' => 'Standard construction bricks',
            'Sand' => 'Fine sand for construction',
            'Tiles' => 'Ceramic tiles for flooring',
            'Paint' => 'Interior and exterior paint',
            'Construction Materials' => 'Various construction materials',
            'Office Stationary (All)' => 'Office supplies and stationery items',
            'Fuel For Generator' => 'Diesel fuel for generator',
            'Electricity Bill' => 'Monthly electricity charges',
            'Internet Bill' => 'Monthly internet service charges',
            'Mobile Bill For Office' => 'Office mobile phone bills',
            'Construction Labor' => 'Labor charges for construction work',
            'Contractor Payment' => 'Payment to contractor for services',
            'Legal Fees' => 'Legal consultation and documentation fees',
        ];

        // Check if we have a specific description
        foreach ($descriptions as $key => $desc) {
            if (stripos($accountName, $key) !== false) {
                return $desc;
            }
        }

        // Default descriptions
        $defaultDescriptions = [
            'Purchase of ' . $accountName,
            'Procurement of ' . $accountName . ' items',
            'Bulk purchase of ' . $accountName,
            'Regular supply of ' . $accountName,
        ];

        return $defaultDescriptions[array_rand($defaultDescriptions)];
    }
}
