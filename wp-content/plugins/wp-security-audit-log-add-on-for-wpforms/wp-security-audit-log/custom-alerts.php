<?php

$custom_alerts = array(
	__( 'WPForms', 'wsal-wpforms' ) => array(
		__( 'Form Content', 'wsal-wpforms' ) => array(

			array(
				5500,
				WSAL_LOW,
				__( 'A form was created, modified or deleted', 'wsal-wpforms' ),
				__( 'Form name %PostTitle% %LineBreak% ID: %PostID% %LineBreak% %EditorLinkForm%', 'wsal-wpforms' ),
				'wpforms_forms',
				'created',
			),

			array(
				5501,
				WSAL_MEDIUM,
				__( 'A field was created, modified or deleted from a form.', 'wsal-wpforms' ),
				__( 'Field name %field_name% %LineBreak% Form name %form_name% %LineBreak% Form ID: %PostID% %LineBreak% %EditorLinkForm%', 'wsal-wpforms' ),
				'wpforms_fields',
				'deleted',
			),

			array(
				5502,
				WSAL_MEDIUM,
				__( 'A form was duplicated', 'wsal-wpforms' ),
				__( 'Source form %OldPostTitle% %LineBreak% New form name %PostTitle% %LineBreak% Source form ID %SourceID% %LineBreak% New form ID: %PostID% %LineBreak% %EditorLinkForm%', 'wsal-wpforms' ),
				'wpforms_forms',
				'duplicated',
			),

			array(
				5503,
				WSAL_LOW,
				__( 'A notification was added to a form, enabled or modified', 'wsal-wpforms' ),
				__( 'Notification name %notifiation_name% %LineBreak% Form name %form_name% %LineBreak% Form ID %PostID% %LineBreak% %EditorLinkForm%', 'wsal-wpforms' ),
				'wpforms_notifications',
				'added',
			),

			array(
				5504,
				WSAL_MEDIUM,
				__( 'An entry was deleted', 'wsal-wpforms' ),
				__( 'Entry email address: %entry_email% %LineBreak% Entry ID: %entry_id% %LineBreak% Form name: %form_name% %LineBreak% Form ID: %form_id% %LineBreak% %EditorLinkForm%', 'wsal-wpforms' ),
				'wpforms_entries',
				'deleted',
			),

			array(
				5505,
				WSAL_LOW,
				__( 'Notifications were enabled or disabled in a form', 'wsal-wpforms' ),
				__( 'All the notifications in the form. %LineBreak% Form name %form_name% %LineBreak% Form ID %PostID% %LineBreak% %EditorLinkForm%', 'wsal-wpforms' ),
				'wpforms_notifications',
				'deleted',
			),

			array(
				5506,
				WSAL_LOW,
				__( 'A form was renamed', 'wsal-wpforms' ),
				__( 'New form name %new_form_name% %LineBreak% Old form name %old_form_name% %LineBreak% Form ID %PostID% %LineBreak% %EditorLinkForm%', 'wsal-wpforms' ),
				'wpforms_forms',
				'renamed',
			),

			array(
				5507,
				WSAL_MEDIUM,
				__( 'An entry was modified', 'wsal-wpforms' ),
				__( 'Entry ID: %entry_id% %LineBreak% From form: %form_name% %LineBreak% Modified field name: %field_name% %LineBreak% Old value: %old_value% %LineBreak% New Value: %new_value% %LineBreak% %EditorLinkEntry%', 'wsal-wpforms' ),
				'wpforms_entries',
				'modified',
			),

			array(
				5508,
				WSAL_HIGH,
				__( 'Plugin access settings were changed', 'wsal-wpforms' ),
				__( 'Access setting: %setting_name% %LineBreak% Type: %setting_type% %LineBreak% Old privileges: %old_value% %LineBreak% New privileges: %new_value%', 'wsal-wpforms' ),
				'wpforms',
				'modified',
			),

			array(
				5509,
				WSAL_HIGH,
				__( 'Currency settings were changed', 'wsal-wpforms' ),
				__( 'Changed the currency %LineBreak% Old currency: %old_value% %LineBreak% New currency: %new_value%', 'wsal-wpforms' ),
				'wpforms',
				'modified',
			),

			array(
				5510,
				WSAL_HIGH,
				__( 'A service integration was added or deleted.', 'wsal-wpforms' ),
				__( 'A service integration %LineBreak% Service: %service_name% %LineBreak% Connection name: %connection_name%', 'wsal-wpforms' ),
				'wpforms',
				'added',
			),

			array(
				5511,
				WSAL_HIGH,
				__( 'An addon was installed, activated or deactivated.', 'wsal-wpforms' ),
				__( 'The addon %addon_name%', 'wsal-wpforms' ),
				'wpforms',
				'activated',
			),

		),
	),
);
