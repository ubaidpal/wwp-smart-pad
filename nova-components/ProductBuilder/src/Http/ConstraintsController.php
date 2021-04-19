<?php

namespace Spp\ProductBuilder\Http;

use App\Http\Controllers\Controller;
use App\Models\OptionConstraintActions;
use App\Models\OrderWindowProductOptions;
use App\Models\ProductOptionConstraints;
use App\Models\ProductOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ConstraintsController extends Controller
{

    /*
    * addConstraint
    * It will add the Constraints Data
    * @constraint  @product_option_id
    * @return
    */

    public function addConstraint(Request $request)
    {
        try {

            $constraints = \GuzzleHttp\json_decode($request->constraint);
            $DB = array();
            foreach ($constraints as $constraint) {

                if ($constraint->id) {
                    $constObj = ProductOptionConstraints::find($constraint->id);
                    if (!$constObj) {
                        $constObj = new ProductOptionConstraints();
                    }
                }

                $constObj->event_type = $constraint->if;
                $constObj->action_type = $constraint->then;
                $constObj->product_option_id = $request->product_option_id;
                $constObj->save();
                $data['id'] = $constObj->id;
                $data['if'] = $constraint->if;
                $data['then'] = $constraint->then;
                $data['conditions'] = $this->addConditions($constraint->conditions, $constObj->id);
                array_push($DB, $data);
            }

            return [
                'success' => true,
                'constraint' => $DB
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    /*
    * addConditions
    * It will add the Condition under the constraints
    * @array => $conditions , constraint id
    * @return
    */

    public function addConditions($conditions, $const_id)
    {
        $DB = array();
        foreach ($conditions as $condition) {

            if ($condition->id) {
                $conditon_data = OptionConstraintActions::find($condition->id);
                if (!$conditon_data) {
                    $conditon_data = new OptionConstraintActions();
                }
            }

            $conditon_data->sub_option_id = $condition->sub_option;
            $conditon_data->option_id = $condition->option_label;
            $conditon_data->option_id = $condition->option_label;
            $conditon_data->option_constraint_id = $const_id;
            $conditon_data->save();

            $cond = array(
                'id' => $conditon_data->id,
                'sub_option' => $condition->sub_option,
                'option_label' => $condition->option_label,
            );

            array_push($DB, $cond);
        }
        return $DB;

    }

    /*
    * deleteConstraint
    * It will delete the complete information of Constraint
    * @constraint id
    * @return
    */

    public function deleteConstraint(Request $request)
    {

        try {
            $id = $request->get('id');
            $constraint = ProductOptionConstraints::find($id);
            if ($constraint) {
                $result = $constraint->delete();
            } else {
                return [
                    'success' => true
                ];
            }

            if ($result) {
                return [
                    'success' => true
                ];
            } else {
                return [
                    'success' => false
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    /*
    * deleteCondition
    * It Will Delete the Only Condition
    * @id
    * @return
    */

    public function deleteCondition(Request $request)
    {

        try {
            $id = $request->get('id');
            $condition = OptionConstraintActions::find($id);
            if ($condition) {
                $result = $condition->delete();
            } else {
                return [
                    'success' => true
                ];
            }

            if ($result) {
                return [
                    'success' => true
                ];
            } else {
                return [
                    'success' => false
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function getCalculation(Request $request, $option_id)
    {
        if ($option_id) {
            $option = ProductOptions::find($option_id);
            if ($option) {
                $data = [];
                $data['optionId'] = $option->connected_to;
                $data['calculation'] = $option->calculation;
                $data['isQuantity'] = $option->is_quantity;
                $data['isDollarValue'] = $option->is_dollar_value;
                $data['isFabricCuts'] = $option->is_fabric_cuts;
                return [
                    'success' => true,
                    'calculation' => $data
                ];

            } else {
                return ['success' => false];
            }
        }
    }

    public function saveCalculation(Request $request, $option_id)
    {
        try{
            if ($option_id) {
                $option = ProductOptions::find($option_id);
                if ($option) {
                    $option->connected_to = $request->optionId;
                    $option->calculation = $request->calculation;
                    $option->is_quantity = $request->is_quantity;
                    $option->is_dollar_value = $request->is_dollar_value;
                    $option->is_fabric_cuts = $request->is_fabric_cuts;
                    $option->save();

                    // update option_id_connected_to under order_window_product_options
                    if ($request->optionId && $request->optionId != 'null') {
                        $windowOption = OrderWindowProductOptions::where('option_id', $option_id)
                            ->update(['option_id_connected_to' => $request->optionId]);
                    }

                    return [
                        'success' => true,
                        'option' => $option
                    ];

                } else {
                    return json_encode(['success' => false]);
                }
            }

        } catch (\Exception $e){
            return json_encode(['success' => false,"error" => $e->getLine().'---'.$e->getMessage() ]);
        }
    }

}
