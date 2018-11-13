<?PHP
	function check_empty_fields($required_fields)
	{
		$form_errors = array();

		foreach ($required_fields as $name_of_field)
		{
			if (!isset($_POST[$name_of_field]) || $_POST[$name_of_field] == NULL)
				$form_errors[] = $name_of_field ." is a required field";
		}
		return $form_errors;
	}

	function check_min_length($fields_to_check)
	{
		$form_errors = array();
		foreach ($fields_to_check as $field_name => $min_length)
		{
			if (strlen(trim($_POST[$field_name])) < $min_length)
			{
				$form_errors[] = $field_name ." is too short, must be $min_length characters long";
			}
		}
		return $form_errors;
	}

	function check_email($data)
	{
		$form_errors = array();
		$key = "email";
		if (array_key_exists($key, $data))
		{
			if ($_POST[$key] != NULL)
			{
				$key = filter_var($key, FILTER_SANITIZE_EMAIL);
				if (!filter_var($_POST[$key], FILTER_VALIDATE_EMAIL))
				{
					$form_errors[] = $key . " is not a valid email address";
				}
			}
		}
		return $form_errors;
	}

/**
 * @param $form_errors
 * @return string
 */
	function show_errors($form_errors)
	{
		$errors = "<P><UL style='color: red;'>";
		
		foreach($form_errors as $error)
		{
			$errors .= "<LI>$error</LI>";
		}
		$errors .= "</UL></P>";
		return $errors;
	}
	function check_password_strength($password)
	{
		$form_error = array();
		if (!preg_match( '~[A-Z]~', $password) ||
            !preg_match( '~[a-z]~', $password) ||
            !preg_match( '~\d~', $password))
		{
			$form_error[] = "Password should contain at least one uppercase, one lowercase character and one number";
		}
		return $form_error;
	}
?>
