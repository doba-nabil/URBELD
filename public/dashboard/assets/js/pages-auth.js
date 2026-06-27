/**
 *  Pages Authentication
 */
'use strict';

document.addEventListener('DOMContentLoaded', function () {
  (() => {
    const formAuthentication = document.querySelector('#formAuthentication');

    // Form validation for Add new record
    if (formAuthentication && typeof FormValidation !== 'undefined') {
      FormValidation.formValidation(formAuthentication, {
        fields: {
          username: {
            validators: {
              notEmpty: {
                message: 'من فضلك ادخل اسم المستخدم'
              },
              stringLength: {
                min: 6,
                message: 'يجب ان يكون اكبر من 6 احرف'
              }
            }
          },
          email: {
            validators: {
              notEmpty: {
                message: 'من فضلك ادخل البريد الالكتروني'
              },
              emailAddress: {
                message: 'من فضلك ادخل بريد الكتروني صالح'
              }
            }
          },
          'email-username': {
            validators: {
              notEmpty: {
                message: 'من فضلك ادخل البريد او اسم المستخدم'
              },
              stringLength: {
                min: 6,
                message: 'لا يجب ان يكون اقل من 6 احرف'
              }
            }
          },
          password: {
            validators: {
              notEmpty: {
                message: 'من فضلك ادخل الرقم السري'
              },
              stringLength: {
                min: 6,
                message: 'يجب ان يكون الرقم السري اكبر من 6 احرف'
              }
            }
          },
          'confirm-password': {
            validators: {
              notEmpty: {
                message: 'من فضلك قم بإعادة الرقم السري'
              },
              identical: {
                compare: () => formAuthentication.querySelector('[name="password"]').value,
                message: 'غير متشابة مع الرقم السري'
              },
              stringLength: {
                min: 6,
                  message: 'يجب ان يكون الرقم السري اكبر من 6 احرف'
              }
            }
          },
          terms: {
            validators: {
              notEmpty: {
                message: 'Please agree to terms & conditions'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.form-control-validation'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('plugins.message.placed', e => {
            if (e.element.parentElement.classList.contains('input-group')) {
              e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        }
      });
    }

    // Two Steps Verification for numeral input mask
    const numeralMaskElements = document.querySelectorAll('.numeral-mask');

    // Format function for numeral mask
    const formatNumeral = value => value.replace(/\D/g, ''); // Only keep digits

    if (numeralMaskElements.length > 0) {
      numeralMaskElements.forEach(numeralMaskEl => {
        numeralMaskEl.addEventListener('input', event => {
          numeralMaskEl.value = formatNumeral(event.target.value);
        });
      });
    }
  })();
});
