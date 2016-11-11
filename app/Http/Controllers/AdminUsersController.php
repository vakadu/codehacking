<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersEditRequest;
use App\Http\Requests\UsersRequest;
use App\Photo;
use App\Role;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $users = User::all();
        //var_dump($users);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $roles = Role::lists('name', 'id') ->all();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersRequest $request)
    {
        //

        if (trim($request ->password) == ''){

            $input = $request ->except('password'); //we put all the requests from form into $input,
            // if the password field is empty, except the password
        }else{

            $input = $request ->all();
        }

        //User::create($request ->all());

        $input = $request ->all();

        if ($file = $request ->file('photo_id')){  //in the case of having a photo

            $name = time() . $file ->getClientOriginalName(); //getting name of the photo and appending time with it
            $file ->move('images', $name); //moving the file to images folder
            $photo = Photo::create(['file' => $name]); // creating the photo and name it file
            $input['photo_id'] = $photo ->id; //pulling the photo id
        }

        $input['password'] = bcrypt($request ->password); //encrypting password

        User::create($input);  //in the case of not having a photo


        return redirect('/admin/users');

        //return $request ->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

        return view('admin.users.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $user = User::findOrFail($id);

        $roles = Role::lists('name', 'id') ->all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsersEditRequest $request, $id)
    {
        //

        $user = User::findOrFail($id);

        if (trim($request ->password) == ''){

            $input = $request ->except('password'); //we put all the requests from form into $input,
            // if the password field is empty, except the password
        }else{

            $input = $request ->all();
        }

        if ($file = $request ->file('photo_id')){

            $name = time() . $file ->getClientOriginalName();
            $file ->move('images', $name);
            $photo = Photo::create(['file' => $name]);
            $input['photo_id'] = $photo ->id;
        }

        $input['password'] = bcrypt($request ->password);

        $user ->update($input);
        return redirect('/admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
