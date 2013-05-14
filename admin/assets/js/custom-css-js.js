var cssCodeMirror = CodeMirror.fromTextArea(jQuery('#custom_css_textarea').get(0), {
			mode:        'css',
			lineNumbers:  true,
			lineWrapping: true,
		}),
		jsCodeMirror = CodeMirror.fromTextArea(jQuery('#custom_js_textarea').get(0), {
			mode:        'javascript',
			lineNumbers:  true,
			lineWrapping: true,
		});