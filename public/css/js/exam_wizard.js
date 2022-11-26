$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    console.log('ready');
    $( "#add-exam-submit" ).click(function() {
        $.ajax({
            url: "/addExam",
            type: "POST",
            data: $("#add-exam-form").serialize(),
            success:function(data){
                $('#add-questions-step .exam-title').text(data.exam_title);
                $('#add-questions-step .exam-id').val(data.exam_id);
                $('#add-questions-step .exam-title').val(data.exam_title);

                loadMultipleChoice();
                loadTrueOrFalse();
                loadEssay();
                loadNumericalExam();
                loadIdentification();
                loadExaminees();
            }
        });
    });

    $(document).on('submit', '#add-question-form', function(event){
        event.preventDefault();
        var exam_type = $('#add-question-form .exam-type').val();
        $.ajax({
            url: "/addQuestion",
            type: "POST",
            data: $(this).serialize(),
            success: function(data){
                if (exam_type == 'Multiple Choice') {
                    loadMultipleChoice();
                }else if (exam_type == 'True or False') {
                    loadTrueOrFalse();
                }else if (exam_type == 'Essay') {
                    loadEssay();
                }else if (exam_type == 'Numerical Exam') {
                    loadNumericalExam();
                }else{
                    loadIdentification();
                }

                $.bootstrapGrowl("<center><i class=\"fa fa-check-square-o\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">" + data.message + "</span></center>", {
                        type: data.success == 1 ? 'success' : 'danger',
                        align: 'center',
                        delay: 4000,
                        width: 450,
                        offset: {from: 'top', amount: 300},
                        stackup_spacing: 20
                    });
                $('#add-question-modal').modal('hide'); 
            }
        });
    });

    $(document).on('click', '#add-question-btn', function(event){
        event.preventDefault();
        var exam_type = $(this).data('exam-type');

        $('#add-question-form .exam-type').val(exam_type);

        var exam_id = $('#add-questions-step .exam-id').val();
        var exam_title = $('#add-questions-step .exam-title').val();

        $('#add-question-form .exam-id').val(exam_id);
        $('#add-question-form .exam-title').val(exam_title);

        $('#add-question-modal .exam_type_id option').filter(function() {
            return $(this).text() == exam_type; 
        }).prop('selected', true);

        if (exam_type == 'Multiple Choice' || exam_type == 'Numerical Exam') {
            $('#add-question-modal .option').show();
            $("#add-question-modal .answerDiv").removeClass('col-md-12').addClass('col-md-8');
            $("#add-question-modal .answerDiv").show();
        }else if(exam_type == 'True or False'){
            $('#add-question-modal .option').hide();
            $("#add-question-modal .answerDiv").removeClass('col-md-8').addClass('col-md-12');
            $("#add-question-modal .answerDiv").show();

            var o = new Option('True', 'True');
            $('#add-question-modal .correct_answer').append($(o).html('True'));
            var o = new Option('False', 'False');
            $('#add-question-modal .correct_answer').append($(o).html('False'));

        }else if(exam_type == 'Essay'){
            $('#add-question-modal .option').hide();
            $("#add-question-modal .answerDiv").hide();
        }else if(exam_type == 'Identification'){
            $('#add-question-modal .option').hide();
            $("#add-question-modal .answerDiv").removeClass('col-md-8').addClass('col-md-12');
            $("#add-question-modal .answerDiv").show();
            $("#add-question-modal .correct_answer").replaceWith('<input type=\"text\" class=\"form-control correct_answer\" name=\"correct_answer\" placeholder=\"Correct Answer (required)\">');
        }else{
            $('#add-question-modal .option').hide();
        }
        $('#add-question-modal').modal('show');
    });

    $(document).on('change', '#add-question-modal .opts', function(){
        $('#add-question-modal .correct_answer').empty();
        var op1 = $('#add-question-modal .option1').val();
        var op2 = $('#add-question-modal .option2').val();
        var op3 = $('#add-question-modal .option3').val();
        var op4 = $('#add-question-modal .option4').val();
        if(op1){
          var o = new Option(op1, op1);
          $('#add-question-modal .correct_answer').append($(o).html(op1));
        }
        if(op2){
          var o = new Option(op2, op2);
          $('#add-question-modal .correct_answer').append($(o).html(op2));
        }
        if(op3){
          var o = new Option(op3, op3);
          $('#add-question-modal .correct_answer').append($(o).html(op3));
        }
        if(op4){
          var o = new Option(op4, op4);
          $('#add-question-modal .correct_answer').append($(o).html(op4));
        }
    });

    $(document).on('click', '#add-examinee-btn', function(event){
        event.preventDefault();
        var exam_id = $('#add-questions-step .exam-id').val();
        $('#add-examinee-form .exam-id').val(exam_id);
        $('#add-examinee-modal').modal('show');
    });

    $(document).on('change', '#add-examinee-form .department', function(){
        $('#add-examinee-form .users').empty();
        $.ajax({
            type: "GET",
            url: "/admin/get_users/" + $(this).val(),
            success: function(data){
                $.each(data.users, function( i, d ) {
                    $('#add-examinee-form .users').append('<option value=\"' + d.id + '\">' + d.employee_name + '</option>');
                });
            }
        });
    });

    $(document).on('submit', '#add-examinee-form', function(event){
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "/addExaminee",
            data: $(this).serialize(),
            success: function(data){
                loadExaminees();
                $.bootstrapGrowl("<center><i class=\"fa fa-check-square-o\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">" + data.message + "</span></center>", {
                        type: 'success',
                        align: 'center',
                        delay: 4000,
                        width: 450,
                        offset: {from: 'top', amount: 300},
                        stackup_spacing: 20
                    });
                $('#add-examinee-modal').modal('hide'); 
            }
        });
    });

    $(document).on('change', '#edit-question-modal .opts', function(){
        $('#edit-question-modal .correct_answer').empty();
        var op1 = $('#edit-question-modal .option1').val();
        var op2 = $('#edit-question-modal .option2').val();
        var op3 = $('#edit-question-modal .option3').val();
        var op4 = $('#edit-question-modal .option4').val();
        if(op1){
          var o = new Option(op1, op1);
          $('#edit-question-modal .correct_answer').append($(o).html(op1));
        }
        if(op2){
          var o = new Option(op2, op2);
          $('#edit-question-modal .correct_answer').append($(o).html(op2));
        }
        if(op3){
          var o = new Option(op3, op3);
          $('#edit-question-modal .correct_answer').append($(o).html(op3));
        }
        if(op4){
          var o = new Option(op4, op4);
          $('#edit-question-modal .correct_answer').append($(o).html(op4));
        }
    });

    $(document).on('click', '#edit-question-btn', function(event){
        event.preventDefault();
        var id = $(this).data('id');
        var exam_type = $(this).data('exam-type');

        if (exam_type == 'Multiple Choice' || exam_type == 'Numerical Exam') {
            $('#edit-question-modal .option').show();
            $("#edit-question-modal .answerDiv").removeClass('col-md-12').addClass('col-md-8');
            $("#edit-question-modal .answerDiv").show();
        }else if(exam_type == 'True or False'){
            $('#edit-question-modal .option').hide();
            $("#edit-question-modal .answerDiv").removeClass('col-md-8').addClass('col-md-12');
            $("#edit-question-modal .answerDiv").show();

            var o = new Option('True', 'True');
            $('#edit-question-modal .correct_answer').append($(o).html('True'));
            var o = new Option('False', 'False');
            $('#edit-question-modal .correct_answer').append($(o).html('False'));

        }else if(exam_type == 'Essay'){
            $('#edit-question-modal .option').hide();
            $("#edit-question-modal .answerDiv").hide();
        }else if(exam_type == 'Identification'){
            $('#edit-question-modal .option').hide();
            $("#edit-question-modal .answerDiv").removeClass('col-md-8').addClass('col-md-12');
            $("#edit-question-modal .answerDiv").show();
            $("#edit-question-modal .correct_answer").replaceWith('<input type=\"text\" class=\"form-control correct_answer\" name=\"correct_answer\" placeholder=\"Correct Answer (required)\">');
        }else{
            $('#edit-question-modal .option').hide();
        }

        $('#edit-question-form .exam-type').val(exam_type);
        data = {id: id}
        $.ajax({
            url: '/getQuestionDetails',
            data: data,
            success: function(data){
                $('#edit-question-form .question-id').val(data.question_id);
                $('#edit-question-form .question').val(data.questions);
                $('#edit-question-form .option1').val(data.option1);
                $('#edit-question-form .option2').val(data.option2);
                $('#edit-question-form .option3').val(data.option3);
                $('#edit-question-form .option4').val(data.option4);

                var op1 = $('#edit-question-form .option1').val();
                var op2 = $('#edit-question-form .option2').val();
                var op3 = $('#edit-question-form .option3').val();
                var op4 = $('#edit-question-form .option4').val();
                if(op1){
                  var o = new Option(op1, op1);
                  $('#edit-question-form .correct_answer').append($(o).html(op1));
                }
                if(op2){
                  var o = new Option(op2, op2);
                  $('#edit-question-form .correct_answer').append($(o).html(op2));
                }
                if(op3){
                  var o = new Option(op3, op3);
                  $('#edit-question-form .correct_answer').append($(o).html(op3));
                }
                if(op4){
                  var o = new Option(op4, op4);
                  $('#edit-question-form .correct_answer').append($(o).html(op4));
                }
            }
        });
        $('#edit-question-modal').modal('show');
    });

    $(document).on('submit', '#edit-question-form', function(event){
        event.preventDefault();
        var exam_type = $('#edit-question-form .exam-type').val();
        $.ajax({
            type: "POST",
            url: "/editQuestion",
            data: $(this).serialize(),
            success: function(data){
                 if (exam_type == 'Multiple Choice') {
                    loadMultipleChoice();
                }else if (exam_type == 'True or False') {
                    loadTrueOrFalse();
                }else if (exam_type == 'Essay') {
                    loadEssay();
                }else if (exam_type == 'Numerical Exam') {
                    loadNumericalExam();
                }else{
                    loadIdentification();
                }

                $.bootstrapGrowl("<center><i class=\"fa fa-check-square-o\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">" + data.message + "</span></center>", {
                        type: 'success',
                        align: 'center',
                        delay: 4000,
                        width: 450,
                        offset: {from: 'top', amount: 300},
                        stackup_spacing: 20
                    });
                $('#edit-question-modal').modal('hide'); 
            }
        });
    });

    $(document).on('click', '#delete-question-btn', function(event){
        event.preventDefault();   
        var exam_type = $(this).data('exam-type');  
        $('#delete-question-form .exam-type').val(exam_type);
        $('#delete-question-form .question_id').val($(this).data('id'));
        $('#delete-question-form .question').text($(this).data('question'));
        $('#delete-question-modal').modal('show'); 
    });

    $(document).on('submit', '#delete-question-form', function(event){
        event.preventDefault();
        var exam_type = $('#delete-question-form .exam-type').val();
        $.ajax({
            type: "POST",
            url: "/deleteQuestion",
            data: $(this).serialize(),
            success: function(data){
                 if (exam_type == 'Multiple Choice') {
                    loadMultipleChoice();
                }else if (exam_type == 'True or False') {
                    loadTrueOrFalse();
                }else if (exam_type == 'Essay') {
                    loadEssay();
                }else if (exam_type == 'Numerical Exam') {
                    loadNumericalExam();
                }else{
                    loadIdentification();
                }

                $.bootstrapGrowl("<center><i class=\"fa fa-check-square-o\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">" + data.message + "</span></center>", {
                        type: 'success',
                        align: 'center',
                        delay: 4000,
                        width: 450,
                        offset: {from: 'top', amount: 300},
                        stackup_spacing: 20
                    });
                $('#delete-question-modal').modal('hide'); 
            }
        });
    });

    loadMultipleChoice();
    loadTrueOrFalse();
    loadEssay();
    loadNumericalExam();
    loadIdentification();
    loadExaminees();

    $(document).on('click', '#go-finalize-btn', function(event){
        event.preventDefault();
        var exam_title = $('#add-exam-form .exam-title').val();
        var exam_group = $('#add-exam-form .exam-group option:selected').text();
        var department = $('#add-exam-form .department option:selected').text();
        var duration = $('#add-exam-form .duration').val();
        var status = $('#add-exam-form .status').val();
        $('#finalize-exam-step .exam-title').text(exam_title);
        $('#finalize-exam-step .exam-group').text(exam_group);
        $('#finalize-exam-step .department').text(department);
        $('#finalize-exam-step .duration').text(duration);
        $('#finalize-exam-step .status').text(status);
        loadFinalExaminees();
    });

    function loadFinalExaminees(page){
        var exam_id = $('#add-questions-step .exam-id').val();
        data = {
            exam_id : exam_id,
        }

        $.ajax({
            url: "/getExaminees?page="+page,
            data: data,
            success:function(data){
                $('#finalize-examinee-table').html(data);
            }
        });
    }

    function loadExaminees(page){
        var exam_id = $('#add-questions-step .exam-id').val();
        data = {
            exam_id : exam_id,
        }

        $.ajax({
            url: "/getExaminees?page="+page,
            data: data,
            success:function(data){
                $('#examinee-table').html(data);
            }
        });
    }

    function loadMultipleChoice(page){
        var exam_id = $('#add-questions-step .exam-id').val();
        var exam_type = 'Multiple Choice';
        var tableView = 'client.tables.questions_multiple_choice_table';

        data = {
            exam_id : exam_id,
            exam_type : exam_type,
            tableView : tableView
        }

        $.ajax({
            url: "/getQuestions?page="+page,
            data: data,
            success:function(data){
                $('#multiple-choice-table').html(data);
            }
        });
    }

    function loadTrueOrFalse(page){
        var exam_id = $('#add-questions-step .exam-id').val();
        var exam_type = 'True or False';
        var tableView = 'client.tables.questions_true_false_table';

        data = {
            exam_id : exam_id,
            exam_type : exam_type,
            tableView : tableView
        }
        
        $.ajax({
            url: "/getQuestions?page="+page,
            data: data,
            success:function(data){
                $('#true-or-false-table').html(data);
            }
        });
    }

    function loadEssay(page){
        var exam_id = $('#add-questions-step .exam-id').val();
        var exam_type = 'Essay';
        var tableView = 'client.tables.questions_essay_table';

        data = {
            exam_id : exam_id,
            exam_type : exam_type,
            tableView : tableView
        }
        
        $.ajax({
            url: "/getQuestions?page="+page,
            data: data,
            success:function(data){
                $('#essay-table').html(data);
            }
        });
    }

    function loadNumericalExam(page){
        var exam_id = $('#add-questions-step .exam-id').val();
        var exam_type = 'Numerical Exam';
        var tableView = 'client.tables.questions_numerical_exam_table';

        data = {
            exam_id : exam_id,
            exam_type : exam_type,
            tableView : tableView
        }
        
        $.ajax({
            url: "/getQuestions?page="+page,
            data: data,
            success:function(data){
                $('#numerical-exam-table').html(data);
            }
        });
    }

    function loadIdentification(page){
        var exam_id = $('#add-questions-step .exam-id').val();
        var exam_type = 'Identification';
        var tableView = 'client.tables.questions_identification_table';

        data = {
            exam_id : exam_id,
            exam_type : exam_type,
            tableView : tableView
        }
        
        $.ajax({
            url: "/getQuestions?page="+page,
            data: data,
            success:function(data){
                $('#identification-table').html(data);
            }
        });
    }

    $('.modal').on('hidden.bs.modal', function(){
        $(this).find('form')[0].reset();
    });

    $('#add-question-modal').on('hidden.bs.modal', function(){
        $('#add-question-modal .correct_answer').empty();
    });

    $('#edit-question-modal').on('hidden.bs.modal', function(){
        $('#edit-question-modal .correct_answer').empty();
    });

    if (!$('#add-exam-form .exam-title').val()) {
        $('#add-exam-form .exam-title').attr('style', "border:#FF0000 1px solid;");
        $('#add-exam-form .exam-title').attr('placeholder', 'Enter Exam Title');
    }

    if (!$('#add-exam-form .duration').val()) {
        $('#add-exam-form .duration').attr('style', "border:#FF0000 1px solid;");
        $('#add-exam-form .duration').attr('placeholder', 'Enter Duration (mins)');
    }

    if (!$('#add-exam-form .passing-mark').val()) {
        $('#add-exam-form .passing-mark').attr('style', "border:#FF0000 1px solid;");
        $('#add-exam-form .passing-mark').attr('placeholder', 'Enter Passing Mark');
    }

    $('#add-exam-submit').hide();

    $('#add-exam-form .req').keyup(function() {
        if ($('#add-exam-form .exam-title').val()) {
            $('#add-exam-form .exam-title').removeAttr('style');
        }else{
            $('#add-exam-form .exam-title').attr('style', "border:#FF0000 1px solid;");
            $('#add-exam-form .exam-title').attr('placeholder', 'Enter Exam Title');
        }

        if ($('#add-exam-form .duration').val()) {
            $('#add-exam-form .duration').removeAttr('style');
        }else{
            $('#add-exam-form .duration').attr('style', "border:#FF0000 1px solid;");
            $('#add-exam-form .duration').attr('placeholder', 'Enter Duration (mins)');
        }

        if ($('#add-exam-form .passing-mark').val()) {
            $('#add-exam-form .passing-mark').removeAttr('style');
        }else{
            $('#add-exam-form .passing-mark').attr('style', "border:#FF0000 1px solid;");
            $('#add-exam-form .passing-mark').attr('placeholder', 'Enter Passing Mark');
        }

        var empty = false;
        $('#add-exam-form .req').each(function() {
            if ($(this).val().length == 0) {
                empty = true;
            }
        });

        if (empty) {
            $('#add-exam-submit').attr('disabled', 'disabled');
            $('#add-exam-submit').hide();
        } else {
            $('#add-exam-submit').attr('disabled', false);
            $('#add-exam-submit').show();
        }
    });
});
  