<?php
class Model_Text extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		//form mail
        'schedule_an_appointpent',
		'name_first',
		'name_last',
		'email',
		'notes',
        'time',
        'date',
        'reserve',
        'thanks',
        'for_reserve',
		'donde',
		'orarios',
		'contactos',
		'designers',

		//dates dealer
        'where',
		'opening_times',
		'contacts',


        //contact store
        'address',
        'postal_code',


        'latitude',
        'longitude',

        //sessions
        'kitchen_tittle',
        'kitchen_description',
        'Living_tittle',
        'Living_description',
        'youth_room_tittle',
        'youth_room_description',

        //links
        'cucine',
        'matrimoniali',
        'bambini',
        'soggiorni',
        'idea',

        'linkcucine',
        'linkmatrimoniali',
        'linkbambini',
        'linksoggiorni',
        'linkidea',


		'user_id',
		'enabled' => ['default' => false, 'form' => 'checkbox'],
		'approved' => ['default' => false, 'form' => 'checkbox'],
		'created_at',
		'updated_at',
	);

	protected static $_observers = [
		'Orm\Observer_CreatedAt' => [
			'events' => ['before_insert'],
			'mysql_timestamp' => false,
		],
		'Orm\Observer_UpdatedAt' => [
			'events' => ['before_save'],
			'mysql_timestamp' => false,
		],
		'Observer_Contentowner' => [
			'events' => ['before_save']
		]
	];

	protected static $_belongs_to = [
		'user' => [
			'key_from' => 'user_id',
			'model_to' => '\Auth\Model\Auth_User',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => false
		]
	];

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('schedule_an_appointpent', 'Schedule An Appointpent', 'required|max_length[255]');
		//$val->add_field('schedule', 'Schedule', 'required|max_length[255]');
		$val->add_field('name_first', 'Name First', 'required|max_length[255]');
		$val->add_field('name_last', 'Name Last', 'required|max_length[255]');
		$val->add_field('email', 'Email', 'required|max_length[255]');
		$val->add_field('notes', 'Notes', 'required|max_length[255]');
        $val->add_field('time', 'Time', 'required|max_length[255]');
        $val->add_field('date', 'Date', 'required|max_length[255]');
        $val->add_field('reserve', 'Reserve', 'required|max_length[255]');
        $val->add_field('thanks', 'Thanks', 'required|max_length[255]');
        $val->add_field('for_reserve', 'For Reserve', 'required|max_length[255]');
		$val->add_field('donde', 'Where Tittle', 'required|max_length[255]');
		$val->add_field('orarios', 'Time Tittle', 'required|max_length[255]');
		$val->add_field('contactos', 'Contacts Tittle', 'required|max_length[255]');

		$val->add_field('where', 'Where', 'required|max_length[255]');
		$val->add_field('opening_times', 'Opening Times', 'required|max_length[255]');
		$val->add_field('contacts', 'Contacts', 'required|max_length[255]');
		$val->add_field('designers', 'Designers', 'required|max_length[255]');
		//$val->add_field('control_code', 'Control Code', 'required|max_length[255]');
		//$val->add_field('newsletter_disclaimer', 'Newsletter Disclaimer', 'required|max_length[255]');
		//$val->add_field('discover_all_products', 'Discover All Products', 'required|max_length[255]');

        $val->add_field('address', 'Address', 'required|max_length[255]');
        $val->add_field('postal_code', 'Postal Code', 'required|max_length[255]');
        //$val->add_field('city', 'City', 'required|max_length[255]');
        //$val->add_field('country', 'Country', 'required|max_length[255]');
        //$val->add_field('continent', 'Continent', 'required|max_length[255]');

        $val->add_field('latitude', 'Latitude', 'required');
        $val->add_field('longitude', 'Longitude', 'required');
        $val->add_field('kitchen_tittle', 'Kitchen Tittle', 'required');
        $val->add_field('kitchen_description', 'Kitchen Description', 'required');
        $val->add_field('Living_tittle', 'Living Tittle', 'required');
        $val->add_field('Living_description', 'Living Description', 'required');
        $val->add_field('youth_room_tittle', 'Youth Room Tittle', 'required');
        $val->add_field('youth_room_description', 'Youth Room Description', 'required');
		$val->add_field('user_id', 'User Id', 'required|valid_string[numeric]');

		return $val;
	}

}
