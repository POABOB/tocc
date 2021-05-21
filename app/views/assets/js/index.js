var qrcode = new QRCode("header__qrcode");

function makeCode () {		
	var curentUrl = window.location.href;
    console.log(curentUrl);
	
	qrcode.makeCode(curentUrl);
}
function getDate () {
    var today = new Date();
    var y = today.getFullYear();
    var m = today.getMonth()+1;
    var d = today.getDate();
    var date = y+'.'+m+'.'+d;
    $('.header__date').text(date);
}
function inputValidate (id, value) { 
    var q = $(`input[name="${id}"]:checked`).map(function(){
        return $(this).val();
    }).get();
    const input = $(`#${id}-${value}_input`).val();
    if(q){
        if(q.indexOf(value) !== -1) {
            return input == '' || input == null ? 1 : 0;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}
function dateInputValidate (id, value) { 
    var q = $(`input[name="${id}"]:checked`).map(function(){
        return $(this).val();
    }).get();
    const inputM = $(`#${id}-${value}_month`).val();
    const inputD = $(`#${id}-${value}_date`).val();
    let error = {
        month: 0,
        date: 0
    };
    if(q){
        if(q.indexOf(value) !== -1) {
            if(inputM == '') {
                error.month = 1;
            }
            if(inputD == ''){
                error.date = 1;
            }
        }
    }
    return error;
}
function secondValidate (id, value) { 
    var q = $(`input[name="${id}"]:checked`).map(function(){
        return $(this).val();
    }).get();
    const input = $(`input[name="${id}-${value}"]:checked`).val();
    if(q){
        if(q.indexOf(value) !== -1) {
            return input == undefined ? 1 : 0;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}
async function formValidate () {
    const error = {
        name: {
            value: $('#name').val() == '' ? true : false,
            name: 'name'
        },
        cellphone: {
            value: ($('#cellphone').val() == '' || $('#cellphone').val().toString().length !== 10) ? true : false,
            name: 'cellphone'
        },
        identity: {
            value: $('#identity').val() == null ? true : false,
            name: 'identity'
        },
        residence: {
            value: $('#residence').val() == null ? true : false,
            name: 'residence'
        },
        q1: {
            value: $('input[name="q1"]:checked').val() == undefined ? true : false,
            name: 'q1'
        },
        q2: {
            value: $('input[name="q2"]:checked').val() == undefined ? true : false,
            name: 'q2'
        },
        q3: {
            value: $('input[name="q3"]:checked').val() == undefined ? true : false,
            name: 'q3'
        },
        q4: {
            value: $('input[name="q4"]:checked').val() == undefined ? true : false,
            name: 'q4'
        }
    };
    const q1_1_input = await inputValidate('q1','1');
    if(q1_1_input == 1) {
        error.q1.value = true;
        $('#q1-1_input').addClass('invalid');
    } else{
        $('#q1-1_input').removeClass('invalid');
    }
    const q1_2_input = await inputValidate('q1','2');
    if(q1_2_input == 1) {
        error.q1.value = true;
        $('#q1-2_input').addClass('invalid');
    } else{
        $('#q1-2_input').removeClass('invalid');
    }
    const q2_6_input = await inputValidate('q2','6');
    if(q2_6_input == 1) {
        error.q2.value = true;
        $('#q2-6_input').addClass('invalid');
    } else{
        $('#q2-6_input').removeClass('invalid');
    }
    const q3_4_input = await secondValidate('q3','4');
    if(q3_4_input == 1) {
        error.q3.value = true;
        $('#q3-4_label').addClass('invalid');
    } else{
        $('#q3-4_label').removeClass('invalid');
    }
    const q3_6_input = await inputValidate('q3','6');
    if(q3_6_input == 1) {
        error.q3.value = true;
        $('#q3-6_input').addClass('invalid');
    } else{
        $('#q3-6_input').removeClass('invalid');
    }
    const q4_1_input = await secondValidate('q4','1');
    if(q4_1_input == 1) {
        error.q4.value = true;
        $('#q4-1_label').addClass('invalid');
    } else{
        $('#q4-1_label').removeClass('invalid');
    }
    const q4_1_2_input = await dateInputValidate('q4-1','2');
    if(q4_1_2_input.month == 1) {
        error.q4.value = true;
        $('#q4-1-2_month').addClass('invalid');
    } else{
        $('#q4-1-2_month').removeClass('invalid');
    }
    if(q4_1_2_input.date == 1) {
        error.q4.value = true;
        $('#q4-1-2_date').addClass('invalid');
    } else{
        $('#q4-1-2_date').removeClass('invalid');
    }
    console.log(error);
    const isError = Object.values(error).some(item => {
        if( item.value === true ) {
            $(`#${item.name}`).addClass('invalid');
            $('html, body').animate({scrollTop: $(`#${item.name}`).offset().top},'fast');
            $(`#${item.name}`).focus();
            return true;
        } else {
            $(`#${item.name}`).removeClass('invalid');
        }
    });
    return isError;
}
async function send () {
    const isError = await formValidate();
    if(!isError) {
        const name = $('#name').val();
        const cellphone = $('#cellphone').val();
        const identity = $('#identity').val();
        const residence = $('#residence').val();
        const travel_histroy = $('input[name="q1"]:checked').val();
        const travel_country = $('#q1-1_input').val();
        const travel_destination = $('#q1-2_input').val();
        const occupation = $('input[name="q2"]:checked').val();
        const occupation_other = $('#q2-6_input').val();
        const contact_history = $('input[name="q3"]:checked').map(function(){
            return $(this).val();
        }).get();
        const contact_multi = $('input[name="q3-4"]:checked').val();
        const contact_other = $('#q3-6_input').val();
        const cluster = $('input[name="q4"]:checked').map(function(){
            return $(this).val();
        }).get();
        const cluster_multi = $('input[name="q4-1"]:checked').val();
        const cluster_date = formatDate($('#q4-1-2_month').val(), $('#q4-1-2_date').val());
        let data = {
            name,
            cellphone,
            identity,
            residence,
            travel_histroy,
            occupation,
            contact_history,
            cluster,
        };
        if(travel_histroy == "1") { 
            data.travel_country = travel_country;
        } else {
            data.travel_country = "";
        }
        if(travel_histroy == "2") { 
            data.travel_destination = travel_destination;
        } else {
            data.travel_destination = "";
        }
        if(occupation == "6") {
            data.occupation_other = occupation_other;
        } else {
            data.occupation_other = "";
        }
        if(contact_history.indexOf("4") !== -1) {
            data.contact_multi = contact_multi;
        } else {
            data.contact_multi = "";
        }
        if(contact_history.indexOf("6") !== -1) {
            data.contact_other = contact_other;
        } else {
            data.contact_other = "";
        }
        if(cluster.indexOf("1") !== -1) { 
            data.cluster_multi = cluster_multi;
            if(cluster_multi.indexOf("2") !== -1) {
                data.cluster_date = cluster_date;
            } else {
                data.cluster_date = "";
            }
        } else {
            data.cluster_multi = "";
            data.cluster_date = "";
        }
        
        axios.post(`/api/add?cusid=${customer_data.cusid}`, data)
          .then(function (response) {
            console.log(response);
            $('#form').hide();
            $('#complete').show();
          })
          .catch(function (error) {
            console.log(error);
            alert(error);
          });
    }
}
function formatDate (month, date) {
    let m = parseInt(month);
    let d = parseInt(date);
    if(month < 10) {
        m = `0${month}`;
    }
    if(date < 10) {
        d = `0${date}`;
    }
    return `${m}-${d}`;
}

getDate();
makeCode();

