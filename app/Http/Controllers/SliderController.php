<?php

namespace App\Http\Controllers;

use App\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{

    private $view_root='admin/slider/';
    public function index()
    {
        $view=view($this->view_root .'index');
        $view->with('sliders',Slider::orderBy('id', 'DESC')->get());
        return $view;
    }

    public function create()
    {
        $view=view($this->view_root .'create');
        return $view;
    }


    public function store(Request $request)
    {
        $image      =$request->file('slider_image');
        $imageName  =$image->getClientOriginalName();
        $directory  ='assets/admin/images/sliders/';
        $image->move($directory, $imageName);

        $slider=new Slider();
        $slider->slider_image = $directory.$imageName;
        $slider->publication_status = $request->publication_status;
        $slider->save();

        return back()->with('message','Insert Successfully');
    }


    public function show(Slider $slider)
    {
        //
    }


    public function edit(Slider $slider)
    {
        $view=view($this->view_root . 'edit');
        $view->with('slider',$slider);
        return $view;
    }

    public function update(Request $request, Slider $slider)
    {
        $image      =$request->file('slider_image');
        if ($image){
            if (file_exists($slider->slider_image)){
                unlink($slider->slider_image);
            }
            $imageName  =$image->getClientOriginalName();
            $directory  ='assets/admin/images/sliders/';
            $image->move($directory, $imageName);
            $slider->slider_image = $directory.$imageName;
        }
        $slider->publication_status = $request->publication_status;
        $slider->update();

        return redirect()->route('slider.index')->with('message','Update Successfully');
    }


    public function destroy(Slider $slider)
    {
        if (file_exists($slider->slider_image)){
            unlink($slider->slider_image);
        }
        $slider->delete();
        return back()->with('message','Delete Successfully');
    }

    public function apiSlider(){
        $slider =Slider::orderBy('id','desc')->get();

        return $slider;
    }
}
