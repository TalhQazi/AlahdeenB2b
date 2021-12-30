jQuery.validator.addMethod(
    "lettersonly",
    function (value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    },
    "Only Letters are allowed."
);

jQuery.validator.addMethod(
    "validEmail",
    function (value, element) {
        return (
            this.optional(element) ||
            /^[+a-zA-Z0-9._-]+@[a-zA-Z.-]+\.[a-zA-Z]{2,3}$/i.test(value)
        );
    },
    "Please enter a valid email"
);

jQuery.validator.addMethod(
    "noSpace",
    function (value, element) {
        return value.indexOf(" ") < 0 && value != "";
    },
    "No space please"
);

jQuery.validator.addMethod("validPhone", function (value, element) {
    let iti = window.intlTelInputGlobals.getInstance(document.querySelector("#phone"));
    return iti.isValidNumber();
}, "Please provide valid phone number.");

jQuery.validator.addMethod(
    "digitsonly",
    function (value, element) {
        return this.optional(element) || /^[0-9 ]+$/i.test(value);
    },
    "Only digits are allowed."
);
