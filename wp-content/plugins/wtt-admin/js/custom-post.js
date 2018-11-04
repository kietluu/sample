(function($) {
    $( document ).ready( function() {
        $('#reject-post-to-writer').on('click', function () {
            return alert('d');
            $('form[name="post"]').append('<input type="hidden" name="reject-post-to-writer" value=1 />');
            $("#reject-post").click();
        });

        $('#reject-post').on('click', function () {
            $.confirm({
                // boxWidth: '500px',
                // useBootstrap: false,
                // columnClass: 'col-md-8 col-md-offset-8',
                containerFluid: true, // this will add 'container-fluid' instead of 'container'
                title: 'Reject Post',
                content: '' +
                '<form  id="reject-form" action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Please input reject reason: </label>' +
                '<div class="col-md-8" ><textarea style="width:100%;min-height:20em"type="text" placeholder="Reason" class="name form-control" required id="reject-reason"/></div>' +
                '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Submit',
                        btnClass: 'btn-blue',
                        action: function () {
                            var reason = this.$content.find('#reject-reason').val();
                            if(!reason){
                                $.alert({
                                    title:'',
                                    content: 'Please input reject reason',
                                    boxWidth: '500px',
                                    useBootstrap: false,

                                })
                                return false;
                            }
                            //
                            $('form[name="post"]').append('<input type="text" name="reason" value="'+reason+'" />');
                            $('form[name="post"]').append('<input type="hidden" name="post_status" value='+$("input[name='hidden_post_status']").val()+' />');
                            $('form[name="post"]').submit();

                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });
        })
        $("#question-categorychecklist input, #question-categorychecklist-pop input").each(
            function () {
                this.type = "radio";
            });
        $('#post').submit(function (e) {
            if($('#hdQue-post-class2:checked').length <1){
                e.preventDefault();
                return alert('Please select answer!');
            }
        })

    } );

})(jQuery);
