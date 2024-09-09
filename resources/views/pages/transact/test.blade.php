<!doctype html>
<html>

<head>
    <meta charset="utf-8">

    <!-- below we are including the jQuery
         and jQuery plugin .js files -->
    <script src=
                "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
    </script>
    <script src=
                "https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js">
    </script>
    <script src=
                "https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js">
    </script>
</head>

<body>
<form class="cmxform"
      id="signupForm"
      method="get"
      action="form-handler.html"
      autocomplete="off">
    <fieldset>
        <legend>GFG sign-up Form</legend>

        <p>
            <label for="firstname">
                Firstname
            </label>
            <input id="firstname"
                   name="firstname"
                   type="text">
        </p>
        <p>
            <label for="lastname">
                Lastname
            </label>
            <input id="lastname"
                   name="lastname"
                   type="text">
        </p>
        <p>
            <label for="username">
                Username
            </label>
            <input id="username"
                   name="username"
                   type="text">
        </p>
        <p>
            <label for="password">
                Password
            </label>
            <input id="password"
                   name="password"
                   type="password">
        </p>
        <p>
            <label for="confirm_password">
                Confirm password
            </label>
            <input id="confirm_password"
                   name="confirm_password"
                   type="password">
        </p>
        <p>
            <label for="email">
                Email
            </label>
            <input id="email"
                   name="email"
                   type="email">
        </p>
        <p>
            <label for="agree">
                Please agree to our policy
            </label>
            <input id="agree"
                   name="agree"
                   type="checkbox">
        </p>
        <p>
            <input class="submit"
                   type="submit"
                   value="submit">
        </p>

    </fieldset>
</form>

<script type="text/javascript">
    $().ready(function () {

        $("#signupForm").validate({

            // In 'rules' user have to specify all the
            // constraints for respective fields
            rules: {
                firstname: "required",
                lastname: "required",
                username: {
                    required: true,
                    minlength: 2 // For length of lastname
                },
                password: {
                    required: true,
                    minlength: 5
                },
                confirm_password: {
                    required: true,
                    minlength: 5,

                    // For checking both passwords are same or not
                    equalTo: "#password"
                },
                email: {
                    required: true,
                    email: true
                },
                agree: "required"
            },
            // In 'messages' user have to specify message as per rules
            messages: {
                firstname: " Please enter your firstname",
                lastname: " Please enter your lastname",
                username: {
                    required: " Please enter a username",
                    minlength:
                        " Your username must consist of at least 2 characters"
                },
                password: {
                    required: " Please enter a password",
                    minlength:
                        " Your password must be consist of at least 5 characters"
                },
                confirm_password: {
                    required: " Please enter a password",
                    minlength:
                        " Your password must be consist of at least 5 characters",
                    equalTo: " Please enter the same password as above"
                },
                agree: "Please accept our policy"
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });

</script>

</body>

</html>
