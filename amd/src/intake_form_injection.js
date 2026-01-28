define(['jquery', 'core/ajax', 'core/notification'], function($, Ajax, Notification) {
    return {
        init: function() {
            this.injectIntakeField();
        },

        injectIntakeField: function() {
            var self = this;
            if (window.location.pathname.includes('/course/modedit.php')) {
                $(document).ready(function() {
                    self.waitForFormAndInject();
                });
            }
        },

        waitForFormAndInject: function() {
            var self = this;
            var attempts = 0;
            
            var checkForm = function() {
                attempts++;
                var form = $('form[data-form-type="course_module"], form.mform, #mform1');
                var generalSection = $('#id_general, fieldset:first');
                
                if (form.length > 0 && generalSection.length > 0 && !$('#id_intake_selection').length) {
                    // Multiple ways to detect quiz or assignment
                    var isQuiz = $('#id_modulename').val() === 'quiz' || 
                                 $('input[name="modulename"][value="quiz"]').length > 0 ||
                                 $('.modtype_quiz').length > 0;
                    var isAssign = $('#id_modulename').val() === 'assign' || 
                                   $('input[name="modulename"][value="assign"]').length > 0 ||
                                   $('.modtype_assign').length > 0;
                    
                    if (isQuiz || isAssign) {
                        self.loadIntakesAndInject(generalSection);
                    }
                } else if (attempts < 50) {
                    setTimeout(checkForm, 200);
                }
            };
            
            checkForm();
        },

        loadIntakesAndInject: function(targetSection) {
            var self = this;
            
            Ajax.call([{
                methodname: 'local_courseworkapi_get_all_intakes',
                args: {activeonly: true}
            }])[0].done(function(response) {
                if (response.intakes && response.intakes.length > 0) {
                    self.createIntakeSection(targetSection, response.intakes);
                }
            }).fail(function(error) {
                console.log('Failed to load intakes:', error);
            });
        },

        createIntakeSection: function(targetSection, intakes) {
            var options = '<option value="0">No intake selected</option>';
            intakes.forEach(function(intake) {
                options += '<option value="' + intake.id + '">' + 
                          intake.name + ' (' + intake.code + ')</option>';
            });

            var intakeHtml = '<fieldset class="collapsible" id="id_intakeheader">' +
                '<legend class="ftoggler">' +
                '<a class="fheader" role="button" aria-controls="id_intakeheader" aria-expanded="false" href="#">' +
                'Intake Assignment' +
                '</a>' +
                '</legend>' +
                '<div class="fcontainer">' +
                '<div class="form-group row fitem">' +
                '<div class="col-md-3">' +
                '<label for="id_intake_selection">Assign to Intake</label>' +
                '</div>' +
                '<div class="col-md-9">' +
                '<select name="intake_selection" id="id_intake_selection" class="form-control">' +
                options +
                '</select>' +
                '<div class="form-text text-muted">Select an intake period to associate this coursework with.</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</fieldset>';

            targetSection.after(intakeHtml);
            
            // Ensure sesskey is present
            var sesskey = $('input[name="sesskey"]').val();
            if (!sesskey) {
                sesskey = M.cfg.sesskey;
                $('form').append('<input type="hidden" name="sesskey" value="' + sesskey + '">');
            }
            
            this.setCurrentIntakeValue();
            this.attachChangeHandler();
        },

        setCurrentIntakeValue: function() {
            var urlParams = new URLSearchParams(window.location.search);
            var cmid = urlParams.get('update');
            
            if (cmid) {
                Ajax.call([{
                    methodname: 'local_courseworkapi_get_current_intake_for_cm',
                    args: {cmid: parseInt(cmid)}
                }])[0].done(function(response) {
                    if (response.intakeid && response.intakeid > 0) {
                        $('#id_intake_selection').val(response.intakeid);
                    }
                }).fail(function(error) {
                    console.log('Failed to get current intake:', error);
                });
            }
        },

        attachChangeHandler: function() {
            $('#id_intake_selection').on('change', function() {
                var value = $(this).val();
                console.log('Intake selection changed to:', value);
            });
        }
    };
});
