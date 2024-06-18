jQuery(document).ready(function($)
{
    $('#referral_code').on('blur',function(){
        var referralCode=$(this).val();

    $.ajax({
        url: referralAjax.ajax_url,
        method:'POST',
        data:{
            action: 'validate_referral_code',
            referral_code: referralCode
        },
        success: function(response){
            if(response.valid){
                $('#referral-code-status').text('✔').css('color','green');
                $('#referral-code-status').text('✘').css('color','red');
            }
        }
    });
});
});
