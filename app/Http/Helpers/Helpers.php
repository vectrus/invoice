<?php





use App\Models\Contact;



if (!function_exists('getContactData')) {
    function getContactData($primary_contact_id)
    {
        if($primary_contact_id != null) {
            $contact = Contact::whereId($primary_contact_id)->first();
            return $contact->firstname . ' ' . $contact->lastname;
        }
        else {
            return null;
        }

    }
}
