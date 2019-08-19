<?php

namespace App\Http\Controllers;

use App\Category;
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

    public function cmsPage($url){
        // Redirect to 404 is the status is disabled
        $cmsPageCount = CmsPage::where(['url' => $url, 'status' => 1])->count();
        if($cmsPageCount == 0){
            abort(404);
        }


        $cmsPageDetails = CmsPage::where('url', $url)->first();
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        return view ('pages.cms_page', compact('cmsPageDetails', 'categories'));
    }
}
