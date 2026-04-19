<?php

namespace App\Http\Controllers;

use App\ProductVariation;
use App\Variation;
use App\VariationTemplate;
use App\VariationValueTemplate;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VariationTemplateController extends Controller
{
    /**
     * Check if request is for Viho template
     */
    protected function isAiTemplateRequest()
    {
        return request()->segment(1) === 'ai-template';
    }

    /**
     * Get the view path for Viho template
     */
    protected function viewPath($view = 'index')
    {
        return 'templates.viho.variation_templates.' . $view;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $variations = VariationTemplate::where('business_id', $business_id)
                        ->with(['values'])
                        ->select('id', 'name', DB::raw('(SELECT COUNT(id) FROM product_variations WHERE product_variations.variation_template_id=variation_templates.id) as total_pv'));

            return Datatables::of($variations)
                ->addColumn('action', function ($row) {
                    $is_ai_template = request()->segment(1) === 'ai-template';
                    $edit_url = $is_ai_template
                        ? route('ai-template.variation-templates.edit', [$row->id])
                        : route('variation-templates.edit', [$row->id]);
                    $delete_url = $is_ai_template
                        ? route('ai-template.variation-templates.destroy', [$row->id])
                        : route('variation-templates.destroy', [$row->id]);

                    if ($is_ai_template) {
                        // Viho template - icon-only buttons
                        $edit_btn = '<button data-href="'.$edit_url.'" class="btn btn-success btn-xs d-inline-flex align-items-center justify-content-center edit_variation_button" title="'.__('messages.edit').'" style="padding: 4px 10px; margin-right: 5px; background-color: #24695c; border-color: #24695c; color: #fff; min-width: 32px; min-height: 32px; border-radius: 4px;"><i class="glyphicon glyphicon-edit" style="font-size: 13px;"></i></button>';
                        $delete_btn = '';
                        if (empty($row->total_pv)) {
                            $delete_btn = '<button data-href="'.$delete_url.'" class="btn btn-danger btn-xs d-inline-flex align-items-center justify-content-center delete_variation_button" title="'.__('messages.delete').'" style="padding: 4px 10px; background-color: #dc3545; border-color: #dc3545; color: #fff; min-width: 32px; min-height: 32px; border-radius: 4px;"><i class="glyphicon glyphicon-trash" style="font-size: 13px;"></i></button>';
                        }
                    } else {
                        // Default template - keep original design
                        $edit_btn = '<button data-href="'.$edit_url.'" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary edit_variation_button"><i class="glyphicon glyphicon-edit"></i> '.__('messages.edit').'</button>';
                        $delete_btn = '';
                        if (empty($row->total_pv)) {
                            $delete_btn = '&nbsp;<button data-href="'.$delete_url.'" class="tw-dw-btn tw-dw-btn-outline tw-dw-btn-xs tw-dw-btn-error delete_variation_button"><i class="glyphicon glyphicon-trash"></i> '.__('messages.delete').'</button>';
                        }
                    }

                    return $edit_btn.$delete_btn;
                })
                ->editColumn('values', function ($data) {
                    $values_arr = [];
                    foreach ($data->values as $attr) {
                        $values_arr[] = $attr->name;
                    }

                    return implode(', ', $values_arr);
                })
                ->removeColumn('id')
                ->removeColumn('total_pv')
                ->rawColumns([2])
                ->make(false);
        }

        return view($this->isAiTemplateRequest() ? $this->viewPath('index') : 'variation.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // For AJAX requests (modal), return modal view
        if (request()->ajax()) {
            return view($this->isAiTemplateRequest() ? $this->viewPath('create') : 'variation.create');
        }
        return view($this->isAiTemplateRequest() ? $this->viewPath('index') : 'variation.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->only(['name']);
            $input['business_id'] = $request->session()->get('user.business_id');
            $variation = VariationTemplate::create($input);

            //craete variation values
            if (! empty($request->input('variation_values'))) {
                $values = $request->input('variation_values');
                $data = [];
                foreach ($values as $value) {
                    if (! empty($value)) {
                        $data[] = ['name' => $value];
                    }
                }
                $variation->values()->createMany($data);
            }

            $output = ['success' => true,
                'data' => $variation,
                'msg' => 'Variation added succesfully',
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => 'Something went wrong, please try again',
            ];
        }

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VariationTemplate  $variationTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(VariationTemplate $variationTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $variation = VariationTemplate::where('business_id', $business_id)
                            ->with(['values'])->find($id);

            $view = $this->isAiTemplateRequest() ? $this->viewPath('edit') : 'variation.edit';
            return view($view)->with(compact('variation'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (request()->ajax()) {
            try {
                $input = $request->only(['name']);
                $business_id = $request->session()->get('user.business_id');

                $variation = VariationTemplate::where('business_id', $business_id)->findOrFail($id);

                if ($variation->name != $input['name']) {
                    $variation->name = $input['name'];
                    $variation->save();

                    ProductVariation::where('variation_template_id', $variation->id)
                                ->update(['name' => $variation->name]);
                }

                //update variation
                $data = [];
                if (! empty($request->input('edit_variation_values'))) {
                    $values = $request->input('edit_variation_values');
                    foreach ($values as $key => $value) {
                        if (! empty($value)) {
                            $variation_val = VariationValueTemplate::find($key);

                            if ($variation_val->name != $value) {
                                $variation_val->name = $value;
                                $data[] = $variation_val;
                                Variation::where('variation_value_id', $key)
                                    ->update(['name' => $value]);
                            }
                        }
                    }
                    $variation->values()->saveMany($data);
                }
                if (! empty($request->input('variation_values'))) {
                    $values = $request->input('variation_values');
                    foreach ($values as $value) {
                        if (! empty($value)) {
                            $data[] = new VariationValueTemplate(['name' => $value]);
                        }
                    }
                }
                $variation->values()->saveMany($data);

                $output = ['success' => true,
                    'msg' => 'Variation updated succesfully',
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => 'Something went wrong, please try again',
                ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');

                $variation = VariationTemplate::where('business_id', $business_id)->findOrFail($id);
                $variation->delete();

                $output = ['success' => true,
                    'msg' => 'Category deleted succesfully',
                ];
            } catch (\Eexception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => 'Something went wrong, please try again',
                ];
            }

            return $output;
        }
    }
}
