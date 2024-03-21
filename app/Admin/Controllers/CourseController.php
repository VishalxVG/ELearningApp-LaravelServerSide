<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\CourseType;
use App\Models\Course;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Tree;

class CourseController extends AdminController
{
    protected function grid()
    {
        $grid = new Grid(new Course());

        $grid->column('id', __('Id'));
        $grid->column('user_token', __('Teacher'))->display(function ($token){
          // Matches user_token from courses and token from User , and return 
          // corrosponding user according to the token
            return  User::where('token' , '=' , $token)->value('name');
        });
        $grid->column('name', __('Name'));
        // image (name , width , height)
        $grid->column('thumbnail', __('Thumbnail'))->image('' , 50 , 50);
        $grid->column('video', __('Video'));
        $grid->column('description', __('Description'));
        $grid->column('type_id', __('Type id'));
        $grid->column('price', __('Price'));
        $grid->column('lesson_num', __('Lesson num'));
        $grid->column('video_length', __('Video length'));
        // $grid->column('follow', __('Follow'));
        // $grid->column('score', __('Score'));
        $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Course::findOrFail($id));

        $show->field('id', __('Id'));
       
        $show->field('name', __('Name'));
        $show->field('thumbnail', __('Thumbnail'));
        
        $show->field('description', __('Description'));
       
        $show->field('price', __('Price'));
        $show->field('lesson_num', __('Lesson num'));
        $show->field('video_length', __('Video length'));
        $show->field('follow', __('Follow'));
        $show->field('score', __('Score'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    // Creating and Editing forms
    protected function form()
    {
        $form = new Form(new Course());
        $form->text('name' , __('Name'));
        // get out categoris
        // key value pair
        // right one is key
        $result = CourseType::pluck('title' , 'id');
        $form->select('type_id', __('Category'))->options($result);

        /*
        $form->select('type_id' , __('Parent Category'))
        ->options((new CourseType())::selectOptions());
        do the same thing as the above code
        */
        $form->image('thumbnail' , __('Thumbnail'))->uniqueName();
        $form->file('video' , __('Video'))->uniqueName();
        $form->text('description' , __('Description'));
        $form->decimal('price', __('Price'));
        $form->number('lesson_num' , __('Lesson Number'));
        $form->number('video_length', __('Video length'));

        // for the posting , who is posting

        $result = User::pluck('name' , 'token');
        $form->select('user_token' , __('Teacher'))->options($result);

        $form->display('created_at' , __('Created At'));
        $form->display('updated_at' , __('Updated At'));

  

        return $form;
    }
}
