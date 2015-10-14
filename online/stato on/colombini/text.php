<?php
class Controller_Admin_Text extends Controller_Admin
{

	public function action_index()
	{
		if (\Auth\Auth::member(6)){
			$data['usuarios'] = \Auth\Model\Auth_Group::find(5)->users;
			$data['texts'] = Model_Text::find('all', ['related' => ['user']]);
		}else{
			$data['texts'] = Model_Text::find('all', ['related' => ['user'], 'where' => ['user_id' => $this->get_current_user_id()]]);
			$data['usuarios'] = \Auth\Model\Auth_Group::find(5)->users;
		}
		$this->template->title = "Texts";
		$this->template->content = View::forge('admin/text/index', $data);

	}

	public function action_view($id = null)
	{
		$data['text'] = Model_Text::find($id, ['related' => ['user']]);
		
		if ($data['text'] && $this->can_access_content($data['text']))
		{
			$this->template->title = "Text";
			$this->template->content = View::forge('admin/text/view', $data);
		}
		else
		{
			Session::set_flash('error', e('Could not view text #'.$id));
			Response::redirect('admin/text');
		}
	}

	public function action_create()
	{
		if (count(Model_Text::find('all', ['where' => ['user_id' => $this->get_current_user_id()]])) < 1)
		{
			if (Input::method() == 'POST') {
				$val = Model_Text::validate('create');

				if ($val->run()) {
					$text = Model_Text::forge([
						'schedule_an_appointpent' => Input::post('schedule_an_appointpent'),
						//'schedule' => Input::post('schedule'),
						'name_first' => Input::post('name_first'),
						'name_last' => Input::post('name_last'),
						'email' => Input::post('email'),
						'notes' => Input::post('notes'),
                        'time' => Input::post('time'),
                        'date' => Input::post('date'),
                        'reserve' => Input::post('reserve'),
                        'thanks' => Input::post('thanks'),
                        'for_reserve' => Input::post('for_reserve'),
						'donde' => Input::post('donde'),
						'orarios' => Input::post('orarios'),
						'contactos' => Input::post('contactos'),
						'designers' => Input::post('designers'),

						'where' => Input::post('where'),
						'opening_times' => Input::post('opening_times'),
						'contacts' => Input::post('contacts'),
						//'control_code' => Input::post('control_code'),
						//'newsletter_disclaimer' => Input::post('newsletter_disclaimer'),
						//'discover_all_products' => Input::post('discover_all_products'),
                        'address' => Input::post('address'),
                        'postal_code' => Input::post('postal_code'),
                        //'city' => Input::post('city'),
                        //'country' => Input::post('country'),
                        //'continent' => Input::post('continent'),
                        'latitude' => Input::post('latitude'),
                        'longitude' => Input::post('longitude'),
                        'kitchen_tittle' => Input::post('kitchen_tittle'),
                        'kitchen_description' => Input::post('kitchen_description'),
                        'Living_tittle' => Input::post('Living_tittle'),
                        'Living_description' => Input::post('Living_description'),
                        'youth_room_tittle' => Input::post('youth_room_tittle'),
                        'youth_room_description' => Input::post('youth_room_description'),
						
                        'cucine' => Input::post('cucine'),
                        'matrimoniali' => Input::post('matrimoniali'),
                        'bambini' => Input::post('bambini'),
                        'soggiorni' => Input::post('soggiorni'),
                        'idea' => Input::post('idea'),

                        'linkcucine' => Input::post('linkcucine'),
                        'linkmatrimoniali' => Input::post('linkmatrimoniali'),
                        'linkbambini' => Input::post('linkbambini'),
                        'linksoggiorni' => Input::post('linksoggiorni'),
                        'latitude' => Input::post('linkidea'),	

						'user_id' => Input::post('user_id'),
						'enabled' => Input::post('enabled', false),
						'approved' => Input::post('approved', false),
					]);

					if ($text && $text->save()) {
						Session::set_flash('success', e('Added text #' . $text->id . '.'));
						Response::redirect('admin/text');
					}
					else
					{
						Session::set_flash('error', e('Could not save text.'));
					}
				}
				else
				{
					Session::set_flash('error', $val->error());
				}
			}

			$users = \Auth\Model\Auth_User::find('all', ['related' => ['group']]);

			$data = ['users' => ['choose-one' => '']];

			foreach ($users as $user) {
				$data['users']['' . $user->id . ''] = $user->username;
			}

			$this->template->title = "Texts";
			$this->template->content = View::forge('admin/text/create', $data);
		}
		else {
			Session::set_flash('error', e('Cannot create another text. (1)'));
			Response::redirect('admin/text');
		}
	}

	public function action_edit($id = null)
	{
		$text = Model_Text::find($id, ['related' => ['user']]);

		if ($this->can_access_content($text)) {

			$val = Model_Text::validate('edit');

			if ($val->run()) {
				$text->schedule_an_appointpent = Input::post('schedule_an_appointpent');
				//$text->schedule = Input::post('schedule');
				$text->name_first = Input::post('name_first');
				$text->name_last = Input::post('name_last');
				$text->email = Input::post('email');
				$text->notes = Input::post('notes');
                $text->time = Input::post('time');
                $text->date = Input::post('date');
                $text->reserve = Input::post('reserve');
                $text->thanks = Input::post('thanks');
                $text->for_reserve = Input::post('for_reserve');
				$text->donde = Input::post('donde');
				$text->orarios = Input::post('orarios');
				$text->contactos = Input::post('contactos');
				$text->designers = Input::post('designers');
				
				$text->where = Input::post('where');
				$text->opening_times = Input::post('opening_times');
				$text->contacts = Input::post('contacts');
				//$text->control_code = Input::post('control_code');
				//$text->newsletter_disclaimer = Input::post('newsletter_disclaimer');
				//$text->discover_all_products = Input::post('discover_all_products');

                $text->address = Input::post('address');
                $text->postal_code = Input::post('postal_code');
                //$text->city = Input::post('city');
                //$text->country = Input::post('country');
                //$text->continent = Input::post('continent');

                $text->latitude = Input::post('latitude');
                $text->longitude = Input::post('longitude');
                $text->kitchen_tittle = Input::post('kitchen_tittle');
                $text->kitchen_description = Input::post('kitchen_description');
                $text->Living_tittle = Input::post('Living_tittle');
                $text->Living_description = Input::post('Living_description');
                $text->youth_room_tittle = Input::post('youth_room_tittle');
                $text->youth_room_description = Input::post('youth_room_description');
				
                 $text->cucine = Input::post('cucine');
                 $text->matrimoniali = Input::post('matrimoniali'); 
                 $text->bambini = Input::post('bambini');
               	 $text->soggiorni = Input::post('soggiorni');
               	 $text->idea = Input::post('idea');

               	 $text->linkcucine = Input::post('linkcucine');
                 $text->linkmatrimoniali = Input::post('linkmatrimoniali'); 
                 $text->linkbambini = Input::post('linkbambini');
               	 $text->linksoggiorni = Input::post('linksoggiorni');
               	 $text->linkidea = Input::post('linkidea');



				$text->user_id = Input::post('user_id');
				$text->enabled = Input::post('enabled', false);
				$text->approved = Input::post('approved', false);

				if ($text->save()) {
					Session::set_flash('success', e('Updated text #' . $id));

					Response::redirect('admin/text');
				} else {
					Session::set_flash('error', e('Could not update text #' . $id));
				}
			} else {
				if (Input::method() == 'POST') {
					$text->schedule_an_appointpent = $val->validated('schedule_an_appointpent');
					//$text->schedule = $val->validated('schedule');
					$text->name_first = $val->validated('name_first');
					$text->name_last = $val->validated('name_last');
					$text->email = $val->validated('email');
					$text->notes = $val->validated('notes');
                    $text->time = $val->validated('time');
                    $text->date = $val->validated('date');
                    $text->reserve = $val->validated('reserve');
                    $text->thanks = $val->validated('thanks');
                    $text->for_reserve = $val->validated('for_reserve');
					$text->donde = $val->validated('donde');
					$text->orarios = $val->validated('orarios');
					$text->contactos = $val->validated('contactos');
					$text->where = $val->validated('where');
					$text->opening_times = $val->validated('opening_times');
					$text->contacts = $val->validated('contacts');
					$text->contacts = $val->validated('contacts');
					//$text->control_code = $val->validated('control_code');
					//$text->newsletter_disclaimer = $val->validated('newsletter_disclaimer');
					//$text->discover_all_products = $val->validated('discover_all_products');

                    $text->address = Input::post('address');
                    $text->postal_code = Input::post('postal_code');
                    //$text->city = Input::post('city');
                    //$text->country = Input::post('country');
                    //$text->continent = Input::post('continent');

                    $text->latitude = $val->validated('latitude');
                    $text->longitude = $val->validated('longitude');
                    $text->kitchen_tittle = $val->validated('kitchen_tittle');
                    $text->kitchen_description = $val->validated('kitchen_description');
                    $text->Living_tittle = $val->validated('Living_tittle');
                    $text->Living_description = $val->validated('Living_description');
                    $text->youth_room_tittle = $val->validated('youth_room_tittle');
                    $text->youth_room_description = $val->validated('youth_room_description');
					
					$text->cucine = $val->validated('cucine');
					$text->matrimoniali = $val->validated('matrimoniali');
					$text->bambini = $val->validated('bambini');
					$text->soggiorni = $val->validated('soggiorni');
					$text->idea = $val->validated('idea');

					$text->linkcucine = $val->validated('linkcucine');
					$text->linkmatrimoniali = $val->validated('linkmatrimoniali');
					$text->linkbambini = $val->validated('linkbambini');
					$text->linksoggiorni = $val->validated('linksoggiorni');
					$text->linkidea = $val->validated('linkidea');


					$text->user_id = $val->validated('user_id');
					$text->enabled = $val->validated('enabled', false);
					$text->approved = $val->validated('approved', false);

					Session::set_flash('error', $val->error());
				}

				$this->template->set_global('text', $text, false);
			}

			$users = \Auth\Model\Auth_User::find('all', ['related' => ['group']]);

			$data = ['users' => []];

			foreach ($users as $user) {
				$data['users']['' . $user->id . ''] = $user->username;
			}

			$this->template->title = "Texts";
			$this->template->content = View::forge('admin/text/edit', $data);
		}
		else
		{
			Session::set_flash('error', e('Could not edit text #'.$id));
			Response::redirect('admin/text');
		}
	}

	public function action_delete($id = null)
	{
		$text = Model_Text::find($id);

		if ($text && $this->can_access_content($text))
		{
			$text->delete();

			Session::set_flash('success', e('Deleted text #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete text #'.$id));
		}

		Response::redirect('admin/text');

	}

	public function action_approve($id = null)
	{
		if (Auth::member(6))
		{
			$text = Model_Text::find($id);
			$text->approved = true;

			if ($text->save(false))
			{
				$this->send_approved_email($text);
				Session::set_flash('success', e('Approved translation #'.$id));
			}
			else
			{
				Session::set_flash('error', e('Could not approve translation #'.$id));
			}
			\Fuel\Core\Response::redirect('admin/dashboard');

		}
		else
		{
			Response::redirect('admin/text');
		}
	}

}
