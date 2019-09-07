<?php

use Illuminate\Database\Seeder;

class CampaignOneConsumers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$names = array(
		    'Christopher',
		    'Ryan',
		    'Ethan',
		    'John',
		    'Zoey',
		    'Sarah',
		    'Michelle',
		    'Samantha',
		    'Scott',
		    'Tommy',
		    'Lidia',
		    'Andy',
		    'Kiel',
		    'Eric',
		);

		//PHP array containing surnames.
		$surnames = array(
		    'Walker',
		    'Thompson',
		    'Anderson',
		    'Johnson',
		    'Tremblay',
		    'Peltier',
		    'Cunningham',
		    'Simpson',
		    'Mercado',
		    'Sellers',
		    'Windon',
		    'Smith',
		    'O\'Brian',
		    'Mendez',
		);

		$this->command->getOutput()->progressStart(25790);

        // Create Elgiible Consumers
        for ($i=0; $i < 4988; $i++) {
			//Generate a random forename.
			$random_name = $names[mt_rand(0, sizeof($names) - 1)];

			//Generate a random surname.
			$random_surname = $surnames[mt_rand(0, sizeof($surnames) - 1)];

			$email = strtolower( preg_replace('/[^a-zA-Z0-9\.\@]/', '', $random_name.'.'.$random_surname.$i.'@example.com') );

			$user = new User;
	        $user->first_name = $random_name;
	        $user->last_name = $random_surname;
	        $user->email = $email;
	        $user->password = str_random(12);
	        $user->save();

	        // Save User Status
            $userMeta = UserMeta::firstOrNew([
                'user_id' => $user->id,
                'meta_key' => 'status',
            ]);
            $userMeta->meta_value = 1;
            $userMeta->save();

            // Save Campaign ID
            $userMeta = UserMeta::firstOrNew([
                'user_id' => $user->id,
                'meta_key' => 'campaign_id',
            ]);
            $userMeta->meta_value = 1;
            $userMeta->save();

            // Save User Campaign UUID
            $userMeta = UserMeta::firstOrNew([
                'user_id' => $user->id,
                'meta_key' => 'campaign_uuid',
            ]);
            $userMeta->meta_value = userUUID($user->id,1);
            $userMeta->save();

            $this->command->getOutput()->progressAdvance();
		}

		// Create Inelgiible Consumers
        for ($i=0; $i < 1025; $i++) {
			//Generate a random forename.
			$random_name = $names[mt_rand(0, sizeof($names) - 1)];

			//Generate a random surname.
			$random_surname = $surnames[mt_rand(0, sizeof($surnames) - 1)];

			$email = strtolower( preg_replace('/[^a-zA-Z0-9\.\@]/', '', $random_name.'.'.$random_surname.$i.'@example.com') );

			$user = new User;
	        $user->first_name = $random_name;
	        $user->last_name = $random_surname;
	        $user->email = $email;
	        $user->password = str_random(12);
	        $user->save();

	        // Save User Status
            $userMeta = UserMeta::firstOrNew([
                'user_id' => $user->id,
                'meta_key' => 'status',
            ]);
            $userMeta->meta_value = 3;
            $userMeta->save();

            // Save Campaign ID
            $userMeta = UserMeta::firstOrNew([
                'user_id' => $user->id,
                'meta_key' => 'campaign_id',
            ]);
            $userMeta->meta_value = 1;
            $userMeta->save();

            // Save User Campaign UUID
            $userMeta = UserMeta::firstOrNew([
                'user_id' => $user->id,
                'meta_key' => 'campaign_uuid',
            ]);
            $userMeta->meta_value = userUUID($user->id,1);
            $userMeta->save();
			$this->command->getOutput()->progressAdvance();
		}

		// Create Activated Consumers
        for ($i=0; $i < 8752; $i++) {
			//Generate a random forename.
			$random_name = $names[mt_rand(0, sizeof($names) - 1)];

			//Generate a random surname.
			$random_surname = $surnames[mt_rand(0, sizeof($surnames) - 1)];

			$email = strtolower( preg_replace('/[^a-zA-Z0-9\.\@]/', '', $random_name.'.'.$random_surname.$i.'@example.com') );

			$user = new User;
	        $user->first_name = $random_name;
	        $user->last_name = $random_surname;
	        $user->email = $email;
	        $user->password = str_random(12);
	        $user->save();

	        // Save User Status
            $userMeta = UserMeta::firstOrNew([
                'user_id' => $user->id,
                'meta_key' => 'status',
            ]);
            $userMeta->meta_value = 2;
            $userMeta->save();

            // Save Campaign ID
            $userMeta = UserMeta::firstOrNew([
                'user_id' => $user->id,
                'meta_key' => 'campaign_id',
            ]);
            $userMeta->meta_value = 1;
            $userMeta->save();

            // Save User Campaign UUID
            $userMeta = UserMeta::firstOrNew([
                'user_id' => $user->id,
                'meta_key' => 'campaign_uuid',
            ]);
            $userMeta->meta_value = userUUID($user->id,1);
            $userMeta->save();
			$this->command->getOutput()->progressAdvance();
		}

		// Create Disabled Consumers
        for ($i=0; $i < 105; $i++) {
			//Generate a random forename.
			$random_name = $names[mt_rand(0, sizeof($names) - 1)];

			//Generate a random surname.
			$random_surname = $surnames[mt_rand(0, sizeof($surnames) - 1)];

			$email = strtolower( preg_replace('/[^a-zA-Z0-9\.\@]/', '', $random_name.'.'.$random_surname.$i.'@example.com') );

			$user = new User;
	        $user->first_name = $random_name;
	        $user->last_name = $random_surname;
	        $user->email = $email;
	        $user->password = Hash::make( 'test123!' );
	        $user->save();

	        $user->markEmailAsVerified();

	        // Save User Status
            $userMeta = UserMeta::firstOrNew([
                'user_id' => $user->id,
                'meta_key' => 'status',
            ]);
            $userMeta->meta_value = 5;
            $userMeta->save();

            // Save Campaign ID
            $userMeta = UserMeta::firstOrNew([
                'user_id' => $user->id,
                'meta_key' => 'campaign_id',
            ]);
            $userMeta->meta_value = 1;
            $userMeta->save();

            // Save User Campaign UUID
            $userMeta = UserMeta::firstOrNew([
                'user_id' => $user->id,
                'meta_key' => 'campaign_uuid',
            ]);
            $userMeta->meta_value = userUUID($user->id,1);
            $userMeta->save();


            // Update Payment Method
            $method = mt_rand(0,2);
	        $userMeta = UserMeta::firstOrNew([
	            'user_id' => $user->id,
	            'meta_key' => 'payment_method',
	        ]);
	        $userMeta->meta_value = $method;
	        $userMeta->save();

	        // Update Secure Data
	        $userMeta = UserMeta::firstOrNew([
	            'user_id' => $user->id,
	            'meta_key' => '_secure_data',
	        ]);
	        if($method == 1) {
	            $userMeta->meta_value = Crypt::encrypt(json_encode([
	                'bank__full_name' => $random_name . ' ' .$random_surname,
	                'bank__bsb' => '061888',
	                'bank__acn' => '91234567',
	            ]));
	        } else if($method == 2) {
	            $userMeta->meta_value = Crypt::encrypt(json_encode([
	                'paypal__email' => $email,
	            ]));
	        } else {
	            $userMeta->meta_value = null;
	        }
	        $userMeta->save();
			$this->command->getOutput()->progressAdvance();
		}

		// Create Registered Consumers
        for ($i=0; $i < 10920; $i++) {
			//Generate a random forename.
			$random_name = $names[mt_rand(0, sizeof($names) - 1)];

			//Generate a random surname.
			$random_surname = $surnames[mt_rand(0, sizeof($surnames) - 1)];

			$email = strtolower( preg_replace('/[^a-zA-Z0-9\.\@]/', '', $random_name.'.'.$random_surname.$i.'@example.com') );

			$user = new User;
	        $user->first_name = $random_name;
	        $user->last_name = $random_surname;
	        $user->email = $email;
	        $user->password = Hash::make( 'test123!' );
	        $user->save();

	        $user->markEmailAsVerified();

	        // Save User Status
            $userMeta = UserMeta::firstOrNew([
                'user_id' => $user->id,
                'meta_key' => 'status',
            ]);
            $userMeta->meta_value = 4;
            $userMeta->save();

            // Save Campaign ID
            $userMeta = UserMeta::firstOrNew([
                'user_id' => $user->id,
                'meta_key' => 'campaign_id',
            ]);
            $userMeta->meta_value = 1;
            $userMeta->save();

            // Save User Campaign UUID
            $userMeta = UserMeta::firstOrNew([
                'user_id' => $user->id,
                'meta_key' => 'campaign_uuid',
            ]);
            $userMeta->meta_value = userUUID($user->id,1);
            $userMeta->save();


            // Update Payment Method
            $method = mt_rand(0,2);
	        $userMeta = UserMeta::firstOrNew([
	            'user_id' => $user->id,
	            'meta_key' => 'payment_method',
	        ]);
	        $userMeta->meta_value = $method;
	        $userMeta->save();

	        // Update Secure Data
	        $userMeta = UserMeta::firstOrNew([
	            'user_id' => $user->id,
	            'meta_key' => '_secure_data',
	        ]);
	        if($method == 1) {
	            $userMeta->meta_value = Crypt::encrypt(json_encode([
	                'bank__full_name' => $random_name . ' ' .$random_surname,
	                'bank__bsb' => '061888',
	                'bank__acn' => '91234567',
	            ]));
	        } else if($method == 2) {
	            $userMeta->meta_value = Crypt::encrypt(json_encode([
	                'paypal__email' => $email,
	            ]));
	        } else {
	            $userMeta->meta_value = null;
	        }
	        $userMeta->save();
			$this->command->getOutput()->progressAdvance();
		}

		$this->command->getOutput()->progressFinish();
    }
}
