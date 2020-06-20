<?php

namespace App\Http\Livewire;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Client;

use Livewire\Component;

class Dialer extends Component
{
  // public $phone_number = '+15105737973';
  public $phone_number = '';
  public $call_button_message = 'Call';

  // ==================================
  
  public function render()
  {
    return view('livewire.dialer');
  }

  // ==================================

  public function makePhoneCall()
  {
    $this->call_button_message = 'Dialing ...';
    try {
      $client = new Client(
        getenv('TWILIO_ACCOUNT_SID'),
        getenv('TWILIO_AUTH_TOKEN')
      );

      try{
        $client->calls->create(
          $this->phone_number,
          getenv('TWILIO_NUMBER'),
          array("url" => "http://demo.twilio.com/docs/voice.xml")
        );
        $this->call_button_message = 'Call Connected!';
        $this->resetDialer();
      }catch(\Exception $e){
        $this->call_button_message = $e->getMessage();
      }
    } catch (ConfigurationException $e) {
      $this->call_button_message = $e->getMessage();
    }
  }

  // ==================================
  
  public function addNumber($number)
  {
    if(strlen($this->phone_number) <= 10){
      $this->phone_number .= $number;
    }
  }

  // ==================================

  public function resetDialer()
  {
    $this->phone_number = '';
    $this->call_button_message = 'Call';
  }

  // ==================================

  public function delete()
  {
    if(strlen($this->phone_number) > 0){
      $this->phone_number = substr($this->phone_number, 0, -1);
    }
  }
}
