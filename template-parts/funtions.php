<?php

function check_duplicate_ticket(ticketNumber) {
  // Get the existing ticket numbers from the 'billing_cotasescolhidas' field
  var existingTicketNumbers = getExistingTicketNumbers();

  // Check if the given ticket number already exists
  if (existingTicketNumbers.includes(ticketNumber)) {
      // Display a message indicating the selected ticket number is not available
      alert("The selected ticket number (" + ticketNumber + ") is already purchased and not available.");

      // Stop payment processing
      return false;
  }

  // Continue with payment processing if the ticket number is not a duplicate
  return true;
}

function getExistingTicketNumbers() {
  // Retrieve the existing ticket numbers from the 'billing_cotasescolhidas' field
  // You can use appropriate WordPress functions to fetch the existing ticket numbers from the field

  // For example, if 'billing_cotasescolhidas' is stored as a comma-separated string,
  // you can split the string and return an array of ticket numbers
  var existingTicketNumbersString = getFieldValue('billing_cotasescolhidas');
  var existingTicketNumbersArray = existingTicketNumbersString.split(',');

  return existingTicketNumbersArray;
}

function getFieldValue(fieldName) {
  // Retrieve the value of the given field from WordPress
  // You can use appropriate WordPress functions to fetch the field value

  // For example, if the field is a text input, you can retrieve its value using jQuery
  var fieldValue = jQuery("#" + fieldName).val();

  return fieldValue;
}
