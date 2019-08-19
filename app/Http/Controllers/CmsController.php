<?php

namespace App\Http\Controllers;

use App\CmsPage;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function addCmsPage(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $cmspage = new CmsPage;
            $cmspage->title = $data['title'];
            $cmspage->url = $data['url'];
            $cmspage->description = $data['description'];
            if(empty($data['status'])){
                $status = 0;
            } else {
                $status = 1;
            }
            $cmspage->status = $status;
            $cmspage->save();
            return redirect()->back()->with('flash_message_success', 'CMS Page Added Successfully');

        }
        return view ('admin.pages.add_cms_page');
    }

    public function viewCmsPages(){
        $cmsPages = CmsPage::get();
        return view ('admin.pages.view_cms_pages', compact('cmsPages'));
    }

    public function editCmsPage(Request $request, $id){
        $cmsPage = CmsPage::findOrFail($id);
        if($request->isMethod('post')){
            $data = $request->all();
            $cmsPage->title = $data['title'];
            $cmsPage->url = $data['url'];
            $cmsPage->description = $data['description'];
            if(empty($data['status'])){
                $status = 0;
            } else {
                $status = 1;
            }
            $cmsPage->status = $status;
            $cmsPage->save();
            return redirect()->back()->with('flash_message_success', 'CMS Page Updated Successfully');

        }
        return view ('admin.pages.edit_cms_page', compact('cmsPage'));
    }

    public function deleteCmsPage($id){
        $cmsPage = CmsPage::findOrFail($id);
        $cmsPage->delete();
        return redirect()->back()->with('flash_message_success', 'CMS Page Deleted Successfully');

    }
}
