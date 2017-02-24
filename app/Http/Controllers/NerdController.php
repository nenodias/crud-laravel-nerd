<?php

namespace App\Http\Controllers;

use View;
use App\Nerd;
use Illuminate\Http\Request;
use Validator;
use Input;
use Session;
use Redirect;

class NerdController extends Controller
{
	public function index()
	{
		$nerds = Nerd::all();
		return View::make('nerds.index')->with('nerds', $nerds);
	}

	public function create()
	{
		return View::make('nerds.create');
	}

	public function store()
	{
		$rules = array(
			'name'      => 'required',
			'email'     => 'required|email',
			'nerd_level'=> 'required|numeric'
		);
		$validator = Validator::make(Input::all(), $rules);

		if($validator->fails())
		{
			return Redirect::to('nerd/create')
				->withErrors($validator)
				->withInput(Input::except('password'));
		} else
		{
			$nerd = new Nerd();
			$nerd->name = Input::get('name');
			$nerd->email = Input::get('email');
			$nerd->nerd_level = Input::get('nerd_level');
			$nerd->save();

			//Redirect
			Session::flash('message','Successfully created nerd!');
			return Redirect::to('nerds');
		}

	}

	public function show($id)
	{
		$nerd = Nerd::find($id);
		return View::make('nerds.show')
			->with('nerd', $nerd);
	}

	public function edit($id)
	{
		$nerd = Nerd::find($id);
		return View::make('nerds.edit')
			->with('nerd',$nerd);
	}

	public function update($id)
	{
		$rules = array(
	            'name'       => 'required',
	            'email'      => 'required|email',
	            'nerd_level' => 'required|numeric'
	        );
		$validator = Validator::make(Input::all(), $rules);

		if($validator->fails())
		{
			return Redirect::to('nerds/'.$id.'/edit')
				->withErrors($validator)
				->withInput(Input::except('password'));
		}else
		{
			$nerd = Nerd::find($id);
			if(!is_null($nerd))
			{
				$nerd->name = Input::get('name');
				$nerd->email = Input::get('email');
				$nerd->nerd_level = Input::get('nerd_level');

				$nerd->save();

				//Redirect
				Session::flash('message', 'Successfully update nerd!');
			}else
			{
				Session::flash('message', 'Nerd not found!');
			}
			return Redirect::to('nerds');

		}
	}

	public function destroy($id)
	{
		$nerd = Nerd::find($id);
		$nerd->delete();

		Session::flash('message','Seccessfully deleted the nerd!');
		return Redirect::to('nerds');
	}

}
