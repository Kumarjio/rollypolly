<script>
  $("#wizard").steps({
    transitionEffect: "slideLeft",
    /*
    onStepChanging: function (event, currentIndex, newIndex)
    {
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex)
        {
            return true;
        }
        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex)
        {
            // To remove error styles
            $("#form1 .body:eq(" + newIndex + ") label.error").remove();
            $("#form1 .body:eq(" + newIndex + ") .error").removeClass("error");
        }
        $("#form1").validate().settings.ignore = ":disabled,:hidden";
        return $("#form1").valid();
    },
    onStepChanged: function (event, currentIndex, priorIndex)
    {
        // Used to skip the "Warning" step if the user is old enough.
        if (currentIndex === 2 && Number($("#age-2").val()) >= 18)
        {
            $("#form1").steps("next");
        }
        // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
        if (currentIndex === 2 && priorIndex === 3)
        {
            $("#form1").steps("previous");
        }
    },

    onFinishing: function (event, currentIndex)
    {
        $("#form1").validate().settings.ignore = ":disabled";
        return $("#form1").valid();
    },
    */
    onFinished: function (event, currentIndex)
    {
        $('#form1').submit();
    }
  });
  //http://www.jquery-steps.com/Examples#advanced-form
</script>