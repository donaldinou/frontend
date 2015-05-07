
$ ->
    $('input[type=radio],input[type=checkbox]').iCheck {
        checkboxClass: 'icheckbox_minimal-orange'
        radioClass:'iradio_minimal-orange'
        increaseArea: '20%' 
    }
    demande_agence = $('input[name="estimation[demande_agence]"],input[name="contact_estimation[demande_agence]"]')

    toggle_contact = (value) ->
        $('.contact_wrapper').toggleClass 'required', value == "1"
        $('.contact_wrapper input').attr 'disabled', if value == "1" then null else 'disabled'
    
    if demande_agence.length > 0
        demande_agence.on 'ifChecked', ->
            toggle_contact this.value
        toggle_contact $('input[name="estimation[demande_agence]"]:checked,input[name="contact_estimation[demande_agence]"]:checked').val()
        
    toggle_type = (t) ->
        all_types = $('input[name="estimation[type]"]').map ( i, e ) ->
            e.value
        .toArray()
        for type in all_types
            $('.only.type_' + type).toggleClass 'required', t == type


    $('input[name="estimation[type]"]').on 'ifChecked', ->
        toggle_type this.value
    if $('input[name="estimation[type]"]:checked').length > 0
        toggle_type $('input[name="estimation[type]"]:checked').val()

    toggle_vue = (display) ->
        $('.only.vue').toggleClass 'required', display

    $('input[name="estimation[vue]"]').on 'ifChecked', ->
        toggle_vue $(this).is(":checked")
    $('input[name="estimation[vue]"]').on 'ifUnchecked', ->
        toggle_vue $(this).is(":checked")
    toggle_vue $('input[name="estimation[vue]"]').is(":checked")


