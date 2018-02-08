/**
 * Created by NAGUI on 14/11/2015.
 */

var f = "http://";
var s = location.host;
if(location.host=="grm.vital-crm.tn:10"){
    var url = f + s ;
}else{
   // var url = f + s + "/GrmProject";
    var url = f + s + "/grm"; 

}
//console.log(url);
function MSg(msg, type) {
    console.log(msg);
    jQuery(".alert").addClass(type);
    jQuery(".alert").show();
    jQuery("#MsgAlert").html(msg);
    window.setTimeout(function () {
            jQuery(".alert").fadeOut('slow')
        },
        10000);
}
function GetLocation(Page) {
    var val = jQuery('#TypeClient').val();
    if (val != "") {
        var url = Page + '&idClient=' + val;
    }
    else url = Page;
    window.location = url;
}
function GetPage(Page) {
    var val = jQuery('#TypeClient').val();
    if (val != "") {
        var url = Page + '&idDel=' + val;
    }
    else url = Page;
    window.location = url;
}
function GetPageI(Page,id) {
    var val = jQuery('#TypeClient').val();
    if (val != "") {
        var url = Page + '&idDel=' + id+'&selVal='+val;
    }
    else url = Page;
    window.location = url;
}
function FindDel() {
    var sect = $("#Secteur").val();
    if (sect != "") {
        // ajax rechercher les délégation associer : 
        $.ajax({
            url: url + "/ajax/FindDel.php",
            type: "POST",
            data: ({
                secteur: sect
            }),
            dataType: 'html',
            success: function (result) {
                $("#DelListe").html(result);
                $("#delegationListe").select2();
                $("#EtablissementListe").select2();
            }
        });


    }
}
function AddPRod(admin) {
    var prod=$("#ProdSelect").val();
    var prodName=$("#ProdSelect :selected").text();
    var prodSerialisable=$("#ProdSelect :selected").attr('rel');
    console.log(prodSerialisable);
    var divApp=$("#ProdListeINp");
    /*if(TotalPoint < prodbonus){
        alert('Point Bonus insufisante ');
        return false;
    }*/
    if(prod!=""){
        $("#"+prod).remove();
        var theId= "prodValue"+prod;
        var theFn = "getMAxQte('"+theId+"')";
        divApp.append('<div class="form-group" id="'+prod+'"> ' +
            '<label>'+
            '<a href="javascript:void(0)" onclick="RemouveDiv('+prod+')" class="btn btn-danger"><i class="fa fa-trash"></i></a> '
            +prodName+'<br/> Quantité : ' +

            '</label>' +
            '<input type="number" name="prodValue['+prod+']" id="'+theId+'" value="1"  min="1" class="form-control QteProd"  onkeyup="'+theFn+'" onmouseup="'+theFn+'" idProd="'+prod+'"> <label class="hidden" id="errorMsgQte'+prod+'"></label>' );
        if(prodSerialisable==1 && admin==1){
            divApp.append('<div id="prodSerie'+prod+'">' +
                '<label>Numero de Série</label>'+
                '<textarea name="Series" class="form-control"></textarea>'+
                '</div>');
        }
        divApp.append('</div>');
    }


}
$('#AddDmd').on('click',function () {
    
})
function VerifyPoints() {

}
function AddPoint(divID) {
    var pIni = $("#PointValIni").text();
    pTot =parseInt(pIni);
    var i=0;
    $('#pBonus input').each(function(){
        var newP = parseInt($(this).val());
        var pbVal = parseFloat($(this).attr('pbval'));
        //alert(newP);
        if(!isNaN(newP)) {
            i++;
            pTot += newP*pbVal;
        }
    });
    if(i==0) {
        var pIni = $("#PointValIni").text();
        pTot =parseInt(pIni);
        $('#pbByDeleg input').each(function(){
            var newP = parseInt($(this).val());
            var pbVal = parseFloat($(this).attr('pbval'));
            //alert(newP);
            if(!isNaN(newP))
                pTot += newP*pbVal;
        });
    }
    $("#PointVal").html(pTot-parseInt(pIni));
    $("#PointValTot").html(pTot);
    $(".totPB").text(pTot);
    $("#TotPoint").val(pTot);
}
function RemouveDiv(divID) {
    $("#"+divID).remove();
}
function StartFiltre() {
    var sect = $("#Secteur").val();
    if (sect != "") {
        // ajax rechercher les délégation associer :
        $.ajax({
            url: url + "/ajax/FiltreDel.php",
            type: "POST",
            data: ({
                secteur: sect
            }),
            dataType: 'html',
            success: function (result) {
                $("#ResFiltre").html(result);
                $(".select2").select2();
                // $("#EtablissementListe").select2();
            }
        });


    } else {
        $("#ResFiltre").html("");
    }
}
function FindDelSimple() {
    var sect = $("#Secteur").val();
    if (sect != "") {
        // ajax rechercher les délégation associer :
        $.ajax({
            url: url + "/ajax/FindDel_1.php",
            type: "POST",
            data: ({
                secteur: sect
            }),
            dataType: 'html',
            success: function (result) {
                $("#DelListe").html(result);
            }
        });


    }
}
function FindEtabSimple() {
    var sect = $("#delegationListe").val();
    if (sect != "") {
        // ajax rechercher les délégation associer :
        $.ajax({
            url: url + "/ajax/FindEtab.php",
            type: "POST",
            data: ({
                secteur: sect
            }),
            dataType: 'html',
            success: function (result) {
                $("#EtabListDiv").html(result);
            }
        });


    }
}
function FindDelListe() {
    var sect = $("#Secteur").val();
    if (sect != "") {
        // ajax rechercher les délégation associer :
        $.ajax({
            url: url + "/ajax/FindDel_3.php",
            type: "POST",
            data: ({
                secteur: sect
            }),
            dataType: 'html',
            success: function (result) {
                $("#DelListe").html(result);
            }
        });


    }
}
function FindDelDemande() {
    var sect = $("#Secteur").val();
    if (sect != "") {
        // ajax rechercher les délégation associer : 
        $.ajax({
            url: url + "/ajax/FindDel_2.php",
            type: "POST",
            data: ({
                secteur: sect
            }),
            dataType: 'html',
            success: function (result) {
                $("#DelListe").html(result);
                $(".select2").select2();
            }
        });


    }
}
function ShwoTable() {
    event.preventDefault();
    $("#Tdetail").show();
}
$(window).load(function () {
    // full load
    $('table.highchart').highchartTable()
});
$(function () {
    $('#cadx').hide();
    $("#Cviste").load(url + '/modules/visites.php', function (response, status, xhr) {
        console.log(xhr.status + " " + xhr.statusText);
        if (status == "200" || xhr.status == "200") {
            $('table.highchart').highchartTable()
            // alert(msg + xhr.status + " " + xhr.statusText);
            console.log(xhr.status + " " + xhr.statusText);
        }
        if (status == "error") {
            // alert(msg + xhr.status + " " + xhr.statusText);
            console.log(xhr.status + " " + xhr.statusText);
        }
    });
    /* $("#SearchBC").keypress(function (e) {
         var key = e.keyCode;
         if(key==13){
             alert("ok");
         }
     })*/
    $("#SearchBC").keydown(function (e) {
        var key = e.keyCode;
        if(key==13){
            var redi= $("#SearchBC").val();
            // get type to redirec
            var type =$('input[name=type]:checked', '#SearchForm').val();
            if(type==1){
                //ajouter au stock :
                window.location="../fournisseur/addStocks&id="+redi;
            }else{
                window.location="EdtitCadeau&id="+redi;
            }
        }
    })
    $('[data-toggle="tooltip"]').tooltip();
    $(window).keydown(function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
    $("#Tdetail").hide();
    $("#linkTable").click(function () {
        $("#Tdetail").show();
        console.log("cliked");
    });
    //  $('table.highchart').highchartTable();
    $(".select2").select2();
    $("#DivRes").hide();
    $("#FilterFreq").click(function (e) {
        e.preventDefault();
        // ajx call for gain time :
        $.ajax({
            url: url + "/ajax/Freq.php",
            type: "POST",
            data: ({
                de: $("#Dedate").val(),
                a: $("#Adate").val()
            }),
            dataType: 'html',
            success: function (result) {
                $("#FreLoad").html(result);
            }

        });

    });
//bootstrap WYSIHTML5 - text editor
    /* $(".textarea").wysihtml5({
     "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
     "emphasis": true, //Italics, bold, etc. Default true
     "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
     "html": false, //Button which allows you to edit the generated HTML. Default false
     "link": false, //Button to insert a link. Default true
     "image": false, //Button to insert an image. Default true,
     "color": true //Button to change color of font
     });*/
    jQuery('#modal-from-dom').on('shown.bs.modal', function () {
        var id = jQuery(this).data('id');
        var name = jQuery(this).data('title');
        var removeBtn = jQuery(this).find('.btn-danger');
        var pkliste = jQuery('textarea#pkliste').val();
        removeBtn.attr('href', removeBtn.attr('href').replace(/(&|\?)SuppID=\d*/, '$1SuppID=' + id));

        jQuery('#debug-url').html('Delete URL: <strong>' + removeBtn.attr('href') + '</strong>');
        jQuery('.TitD').html(name);

    });
    jQuery('.PkAdd').click(function () {
        var pkliste = jQuery('textarea#pkliste').val();
        var href = jQuery('.PkAdd').attr('href');
        jQuery('a.PkAdd').attr('href', href + '&prk=' + pkliste);

    });
    $('.confirm-delete').on('click', function (e) {
        e.preventDefault();

        var id = $(this).data('id');
        var name = $(this).data('title');
        // jQuery('#debug-url').html(name);
        //  alert(id);
        $('#modal-from-dom').data('id', id).data('title', name).modal('show');
    });

    $.fn.bootstrapSwitch.defaults.onColor = 'success';
    $.fn.bootstrapSwitch.defaults.offColor = 'danger';
    $(".previlege").bootstrapSwitch();
    $('.switcher').bootstrapSwitch('state'); // true || false
    $('.switcher').bootstrapSwitch('toggleState');
    $('.switcher').bootstrapSwitch('setState', false); // true || false
    //filter
    $("#FilterPros").click(function (event) {
        //event.preventDefault();
        // ajax call 
        $("#ListeDiv").html('');
        var secteur = $("#Secteur").val();
        var spec = $("#Spec").val();
        var del = $("#delegationListe").val();
        if (secteur !== "" || spec != "") {
            $("#MsgPRo").hide();
            $("#DivRes").show();
            $.ajax({
                url: url + "/ajax/AjaxProspects.php",
                type: "POST",
                data: ({
                    secteur: $("#Secteur").val(),
                    spec: $("#Spec").val(),
                    del: del
                }),
                dataType: 'html',
                success: function (result) {
                    $("#ListeDiv").html(result);
                }

            }).done();

        } else {
            $("#MsgPRo").append("Merci de choisir le secteur et la spécialité").show();
        }
    });
    /*$("#ListeDiv").bind('change','#AllLink',function(ee){  
     $("INPUT[id*='PRosSel_']").attr('checked', $('#AllLink').is(':checked'));
     //  $(".checkbox1").prop('checked', $(this).prop("checked"));
     }); $('#AllLink').change(function() {  
     var checkboxes = $(this).closest('form').find(':checkbox');
     if($(this).is(':checked')) {
     checkboxes.prop('checked', true);
     } else {
     checkboxes.prop('checked', false);
     }
     });*/
    $('#ListeDiv').on('change', '#AllLink', function (ee) {
        console.log('dd' + $('#AllLink').is(':checked'));
        // var checkboxes = $(this).closest('form').find(':checkbox');
        if ($('#AllLink').is(':checked')) {
            $(".checkbox1").prop('checked', true);
        } else {
            $(".checkbox1").prop('checked', false);
        }
    });
    $('#ListeDiv').on('change', '.checkbox1', function (ee) {
        if ($(this).is(':checked')) {
            console.log($('#AllLink').is(':checked'));
        } else {
            $("#AllLink").prop('checked', false);
        }
    });


    $("#DelListe").on('change', '#EtabSelc', function () {
        if ($("#EtabSelc").find(':selected').text() == "----") {
            $("#adresse").val('').prop('readonly', '');
        } else {
            $("#adresse").val($("#EtabSelc").find(':selected').text()).prop('readonly', 'readonly');
        }


    });
    $("#DelListe").on('change', '#delegationListe', function () {
        $("#code_postal").val($("#delegationListe").find(':selected').attr('rel')).prop('readonly', 'readonly');
        $("#adresse").val('').prop('readonly', '');
    });
    $("#DelListe").on('change', '#EtabSelc', function () {
        $("#code_postal").val($("#EtabSelc").find(':selected').attr('rel')).prop('readonly', 'readonly');

    });
});
function PrintDiv() {
    $(".box-footer").hide();
    $(".direct-chat-img").hide();
    var divToPrint = document.getElementById('DivToPrint');
    var newWin = window.open();
    newWin.document.write(divToPrint.innerHTML);
    newWin.document.close();
    newWin.focus();
    newWin.print();
    newWin.close();
    $(".box-footer").show();
    $(".direct-chat-img").show();
}
function Jouv(da) {
    console.log('da', da);
    var par = $("#" + da);
    console.log(par);
    $.ajax({
        url: url + "/ajax/AddJouv.php",
        type: "POST",
        data: ({dateJ: da}),
        dataType: 'html',
        success: function (result) {
            if (par.hasClass('active')) {
                par.removeClass('active');
            } else {
                par.addClass('active');
            }
        }

    })
}
function GetProdListe(){
    var gamme = $("#gamme").val();
    if(!gamme) return false;
    $.ajax({
        type:'POST',
        data:{Gammeid:gamme},
        url:url+'/ajax/Bonus/prodListe.php',
        success:function (data) {
            $("#ProdACmd").html(data);
            $('#ProdSeaC').select2();
            $('#BtnCmd').show()
        }
    })
}
function getMAxQte(qte){
    //alert('ok');
    if (typeof qte === "undefined" || qte === null) {
        qte = $("#qte");
    } else {
        qte=$("#"+qte);
    }
    if(qte.attr('idProd')) {
        var idProd=qte.attr('idProd');
    } else {
        var idProd=$("#ProdSeaC").val();
    }
    $.ajax({
        url:url+'/ajax/Bonus/getMaxQte.php',
        type:'POST',
        data:{product:idProd,qte:qte.val()},
        success:function (res) {
            var data = jQuery.parseJSON(res);
            if(data.status=='success') {
                $('#BtnEchant').removeAttr("disabled");
                $('#ProdSelect').removeAttr("disabled");
                $('#gamme').removeAttr("disabled");
                if($('#errorMsgQte'+idProd).length) {
                    $('#errorMsgQte'+idProd).addClass('hidden');
                } else {
                    $('#errorMsgQte').addClass('hidden');
                }
            } else {
                $('#BtnEchant').attr("disabled", "disabled");
                $('#ProdSelect').attr("disabled", "disabled");
                $('#gamme').attr("disabled", "disabled");
                if($('#errorMsgQte'+idProd).length) {
                    $('#errorMsgQte'+idProd).text(data.message);
                    $('#errorMsgQte'+idProd).removeClass('hidden');
                } else {
                    $('#errorMsgQte').text(data.message);
                    $('#errorMsgQte').removeClass('hidden');
                }
            }
        }
    })
}
function GetProdListeforEdit(){
    var gamme = $("#gamme").val();
    if(!gamme) return false;
    $.ajax({
        type:'POST',
        data:{Gammeid:gamme},
        url:url+'/ajax/validationDmd/prodListe.php',
        success:function (data) {
            $("#ProdACmd").html(data);
            $('#ProdSeaC').select2();
            $('#BtnCmd').show()
        }
    })
}

function RediPage(id){
    window.location='avecPBonus&id='+id;
}
function ShowDiv(id) {
    $("#cadx").hide();
    $("#prodL").hide();
    $("#"+id).show();
}
function PbAdd(page) {
    if (typeof page === "undefined" || page === null) {
        page = "AddBonusSession.php";
    }
    var TotPoint =$("#TotPoint").val();
    var ponits = new Array();
    $('#pBonus input').each(function(){
        var id = $(this).attr('rel');
        ponits[id] = $(this).val();
    });
    var Point =$("#PintC").val();
    var newPb =$("#PointVal").text();
    var cdxSansPB =$("#cdxSansPB").val();
    var type;
    var ProdSeaC;
    var qte;
    var Obs = $("#ObsAdm").val();
    var client =$('#client').val();
    var idDemande =$('#idDemande').val();
    if(TotPoint<=0){
        MSg('Merci de saisir le nombre de points bonus','alert-danger');
        return false;
    }
    var prodbonus=0;
    if($('#TypeProd').is(':checked')){
        type=2; // c'est un produits
        ProdSeaC=$("#ProdSeaC").val();
        console.log(ProdSeaC);
        qte= $("#qte").val();
        prodbonus=10;
    }else{
        type=1;
        ProdSeaC=$("#CdxSelect").val();
        qte=$("#qteC").val();
        prodbonus = $("#CdxSelect :selected").attr('bonus');
    }
    if(!ProdSeaC || !qte || !client){
        MSg('Merci de choisir le produits / articles','alert-danger');
        return false;
    }else{
        //ajax pour la session.
        $.ajax({
            url:url+'/ajax/Bonus/'+page,
            type:'POST',
            data:{type:type,qte:qte,client:client,ProdSeaC:ProdSeaC,TotPoint:TotPoint,prodbonus:prodbonus,Point:Point,Obs:Obs,ponits:ponits,newPb:newPb,idDemande:idDemande,cdxSansPB:cdxSansPB },
            success:function (data) {
                $("#ListeProdSessions").html(data);
            },
            error:function () {
                Msq('Un problème est survenu merci de refraichir la page','alert-danger');
            }

        })
    }
}
function FinalisationPb() {
    $.ajax({
        url:url+'/ajax/Bonus/validationDemande.php',
        type:'POST',
        data:{
            idDemande: $('#idDemande').val(),
            idRemise: $('#id_remise').val(),
            ObsAdm: $('#ObsAdm').val(),
        },
        success:function (data) {
            MSg('Demande valider','alert-success');
            window.location = '../gestionDesDemandes/printDoc&idDemande='+data;
        },
        error:function () {
            MSg('Un problème est survenu merci de refraichir la page','alert-danger');
        }

    })
}
function validerDmdCdx() {
    $.ajax({
        url:url+'/ajax/Bonus/ValidateListeBonus.php',
        type:'POST',
        data:{
            obs: $('#Obs').val(),
        },
        success:function (data) {
            MSg('Demande valider','alert-success');
            window.location = '../gestionDesDemandes/Liste';
        },
        error:function () {
            MSg('Un problème est survenu merci de refraichir la page','alert-danger');
        }

    })
}

function SelectProd() {
    var famille = $("#FamillesProd").val();
    var bonusP =$("#PintC").val();
    if(famille==1 && (bonusP=="" || bonusP==0)){
        MSg('Merci de ajouter les points bonus ou choisir une autre famille','alert-danger');
        return false;
    }
    if(famille==3){
        MSg('Vous ne pouvez plus faire de Echantillat par porspect','alert-danger');
        return false;
    }
    // test quota if has quota or not :
    $("#ProdListeINp").html('');
    $.ajax({
        url: url + "/ajax/ProdInfos.php",
        type: "POST",
        data: ({
            famille: famille
        }),
        dataType: 'html',
        success: function (result) {
            $("#ProdInfos").html(result);
            $("#ProdSelect").select2();
        }
    });
}
function AddPRodDemande(admin) {
    var prod = $("#ProdSelect").val();
    var prodName = $("#ProdSelect :selected").text();
    var prodSerialisable = $("#ProdSelect :selected").attr('rel');
    var prodbonus = $("#ProdSelect :selected").attr('bonus');
    var TotalPoint = $("#TotPoint").val();
    console.log(prodSerialisable);
    console.log(prodbonus);
    var divApp = $("#ProdListeINp");
    if (prodbonus != "" && TotalPoint < prodbonus && TotalPoint == "") {
        alert('Point Bonus insufisante ');
        return false;
    }
    if (prod != "") {
        $("#" + prod).remove();

        divApp.append('<div class="form-group" id="' + prod + '"> ' +
            '<label>' +
            '<a href="#" onclick="RemouveDiv(' + prod + ')" class="btn btn-danger"><i class="fa fa-trash"></i></a> '
            + prodName + '<br/> Quantité : ' +

            '</label>' +
            '<input type="number" name="prodValue[' + prod + ']" value="1"  min="1" class="form-control QteProd" onchange="VerifyPoints()">');
        if (prodSerialisable == 1 && admin == 1) {
            divApp.append('<div id="prodSerie' + prod + '">' +
                '<label>Numero de Série</label>' +
                '<textarea name="Series" class="form-control"></textarea>' +
                '</div>');
        }
        divApp.append('</div>');
    }
}
$('#BtnPromo').click(function () {
    // add to cmd liste by add to sessions :
    var ProdSeaC= $("#ProdSeaC").val();
    var qte= $("#qte").val();
    var type=$('#uType').val();
    var delegue=$('#delegue').val();
    if(ProdSeaC !='' && qte>=1 && delegue!=''){
        $.ajax({
            type:'POST',
            url:url+'/ajax/promo/addPromoSession.php',
            data:{ProdSeaC:ProdSeaC,qte:qte,type:type,delegue:delegue},
            success:function (data) {
                $("#ListeProdSessions").html(data);
            },
            error:function () {
                MSg('Une erreur c\'est produits ','alert-danger')
            }
        });
    }else {
        MSg('Merci de bien saisir les données','alert-warning');
    }
});
$('#ListeProdSessions').on('click','#BtnValiderPromo',function () {
// appeler la page qui gère l'ajout des :

    $.ajax({
        type:'POST',
        url:url+'/ajax/promo/validateListePromo.php',
        data:{MonthValue:1},
        success:function (data) {
            MSg('Votre demande est enregister ','alert-success');
           window.location ='../gestionDesDemandes/dmdPromotionnel';
        },
        error:function () {
            MSg('Une erreur c\'est produits ','alert-danger')
        }
    });
});
$('#BtnEchant').click(function () {
    // add to cmd liste by add to sessions :
    var ProdSeaC= $("#ProdSeaC").val();
    var qte= $("#qte").val();
    var type=$('#uType').val();
    var delegue=$('#delegue').val();

    if(ProdSeaC !='' && qte>=1){
        $.ajax({
            type:'POST',
            url:url+'/ajax/echantiants/addEchantSession.php',
            data:{ProdSeaC:ProdSeaC,qte:qte,type:type,delegue:delegue},
            success:function (data) {
                $("#ListeProdSessions").html(data);
            },
            error:function () {
                MSg('Une erreur c\'est produits ','alert-danger')
            }
        });
    }else {
        MSg('Merci de bien saisir les données','alert-warning');
    }
});
$('#ListeProdSessions').on('click','#BtnValiderEchant',function () {
// appeler la page qui gère l'ajout des :
    var MonthValue=$('#MonthValue').val();
    console.log('datte',MonthValue);
    if(MonthValue){
        $.ajax({
            type:'POST',
            url:url+'/ajax/echantiants/validateDmdEchant.php',
            data:{MonthValue:MonthValue},
            success:function (data) {
                MSg('Votre demande est enregister ','alert-success');
                window.location ='../gestionDesDemandes/listedmdEchantillons';

            },
            error:function () {
                MSg('Une erreur c\'est produits ','alert-danger')
            }
        });
    }else{
        MSg('Date incorrect ','alert-danger');
    }
});
$(function() {
    $('.validQte').click(function() {
        var qte=$(this).parent('.editStock').find('.qteAdded').val();
        var prodId=$(this).parent('.editStock').find('.qteAdded').attr('rel');
        var prodName=$('table').find('#'+prodId).html();
        $ancQte=$('table').find('.'+prodId).html();
        var prodAdded='';
        if ( $('.prodInsertListe li').length > 1 ) {
            $('#validBnEntr').removeAttr("disabled");
        } else {
            $('#validBnEntr').attr('disabled',true);
        }
        $('#listeSubmitProds input').each(function(){
            if($(this).attr("name")==prodId) {
                var ancQteEntr=$('.prodInsertListe').find('.'+prodId).html();
                $(this).val(qte);
                $ancQte=parseInt($ancQte)-parseInt(ancQteEntr);
                $ancQte=parseInt($ancQte)+parseInt(qte);
                $('table').find('.'+prodId).html($ancQte);
                $('.prodInsertListe').find('.'+prodId).html(qte);
                $(this).parent('.editStock').find('.qteAdded').val('');
                prodAdded=1;
            }
        });
        if(prodAdded=='') {
            $ancQte=parseInt($ancQte)+parseInt(qte);
            $('table').find('.'+prodId).html($ancQte);
            $(this).parent('.editStock').find('.qteAdded').val('');
            $('#listeSubmitProds').append("<input type='hidden' value='"+qte+"' name='"+prodId+"'>");
            $('.prodInsertListe').append('<li>'+prodName+' => La quantité entré => <span class="'+prodId+'" >'+qte+'</span></li>');
        }
    });
});
$(function() {
    $('.cancelDmd').click(function(ev) {
        var href = $(this).attr('href');
        if (!$('#dataConfirmModal').length) {
            $('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Annuler la demande</h3><br/></h3></div><div class="modal-body"><b></b></div><div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Non</button><a class="btn btn-danger" id="dataConfirmOK">Oui</a></div></div></div></div>');
        }
        $('#dataConfirmModal').find('.modal-body b').empty().append($(this).attr('data-confirm'));
        $('#dataConfirmOK').attr('href', href);
        $('#dataConfirmModal').modal({show:true});
        return false;
    });
});
$(document).ready(function () {
    size_li = $("#myTable tr").size();
    x=3;
    $('#myTable tr:lt('+x+')').show();
    $('.loadAll').click(function () {
        x= size_li-x;
        $('#myTable tr:lt('+x+')').show();
    });
    $('.loadMore').click(function () {
        x= (x+5 <= size_li) ? x+5 : size_li;
        $('#myTable tr:lt('+x+')').show();
    });
    $('.showLess').click(function () {
        if(x>=100) {
            x=4;
            $('#myTable tr').not(':lt('+x+')').hide();
        } else {
            x=(x-5<0) ? 3 : x-5;
            $('#myTable tr').not(':lt('+x+')').hide();
        }

    });
});
function myFunction(id,index) {
    // Declare variables
    var input, filter, table, tr, td, i;
    input = document.getElementById(id);
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[index];
        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "table-row";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
$(function () {
    var t = false
    $('.qteAdded').focus(function () {
        var $this = $(this);
        t = setInterval(
            function () {
                if (($this.val() < 1 || $this.val() > 10) && $this.val().length != 0) {
                    if ($this.val() < 1) {
                        $this.val('')
                    }
                }
            }, 50)
    });
    $('.qteAdded').keyup(function () {
        if($('.qteAdded').val()!='') {
            $('.validQte').removeAttr("disabled");
        } else {
            $('.validQte').attr('disabled',true);
        }
    });
    $('.qteAdded').blur(function () {
        if (t != false) {
            window.clearInterval(t);
            t = false;
        }
        if($('.qteAdded').val()!='') {
            $('.validQte').removeAttr("disabled");
        } else {
            $('.validQte').attr('disabled',true);
        }
    });
});
