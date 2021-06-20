<?php
?>
Contacts:
<?php

foreach ($contacts as $recipient_name=>$contact){
    echo $contact['recipient_name'] . ', ';
}
?>
<input type="button" value="Add new contact" onmouseover="showContactForm()">
<span id="addContactForm" onmouseleave="hideContactForm()" hidden>
        <input id='newcontactname' type='text' value='Friends username' onfocus='clearBox("newcontactname")' onblur='setToDefaultIfEmpty("newcontactname")'>
        <input type='submit' value='Add' onclick='addContact()'/>
</span>
