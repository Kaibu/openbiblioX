/*function test(){

 alert(document.getElementById('anzahl').value);

 }*/
/*************setzt eingabefelder nach anzahl um eins hoch oder niedriger *******************************/

function showslogan(anzahl) {
    //document.write(document.getElementById('slogan1').getAttribute('value');
    //document.getElementById('slogan1').setAttribute("type", "hidden");
    //alert(anzahl);
    slogan_anzahl = anzahl
    if (anzahl == 1) {
        document.getElementById('slogan1').setAttribute("type", "hidden");
        document.getElementById('slogan2').setAttribute("type", "hidden");
        document.getElementById('slogan3').setAttribute("type", "hidden");
        document.getElementById('slogan4').setAttribute("type", "hidden");
        document.getElementById('slogan1').value = "";
        document.getElementById('slogan2').value = "";
        document.getElementById('slogan3').value = "";
        document.getElementById('slogan4').value = "";
    }
    if (anzahl == 2) {
        document.getElementById('slogan1').setAttribute("type", "text");
        document.getElementById('slogan2').setAttribute("type", "hidden");
        document.getElementById('slogan3').setAttribute("type", "hidden");
        document.getElementById('slogan4').setAttribute("type", "hidden");
        document.getElementById('slogan2').value = "";
        document.getElementById('slogan3').value = "";
        document.getElementById('slogan4').value = "";
    }
    if (anzahl == 3) {
        document.getElementById('slogan1').setAttribute("type", "text");
        document.getElementById('slogan2').setAttribute("type", "text");
        document.getElementById('slogan3').setAttribute("type", "hidden");
        document.getElementById('slogan4').setAttribute("type", "hidden");
        document.getElementById('slogan3').value = "";
        document.getElementById('slogan4').value = "";
    }
    if (anzahl == 4) {
        document.getElementById('slogan1').setAttribute("type", "text");
        document.getElementById('slogan2').setAttribute("type", "text");
        document.getElementById('slogan3').setAttribute("type", "text");
        document.getElementById('slogan4').setAttribute("type", "hidden");
        document.getElementById('slogan4').value = "";
    }
    if (anzahl == 5) {
        document.getElementById('slogan1').setAttribute("type", "text");
        document.getElementById('slogan2').setAttribute("type", "text");
        document.getElementById('slogan3').setAttribute("type", "text");
        document.getElementById('slogan4').setAttribute("type", "text");
    }
}
/**********************************************************************************
 Auswahlfelder der den Select/Dropdown zuordnen und das jeweilige selected setzen
 **********************************************************************************/

function set_dropdown_selected() {

    var anzahl = document.getElementById('anzahl_slogan').getElementsByTagName('option')[6].text;
    //alert(anzahl);
    if (anzahl != 0) {
        showslogan(anzahl);
    }

}


/***********Suchfunktion**** mit erstellung von Select feldern*****************/
function hinzu() {
    var list = get_list();
    //var list ='<?php echo $list_slogans;?>';
    var list_safe = list;
    /*die Liste wird zwischen gespeichert um groß und kleinschreibung im vergleich nicht zu verändern*/
    list = list.toLowerCase();
    var Eintrag = "";
    var Auswahlliste = "";
    var slogan0 = "";
    var matches = "";
    //alert(Inputfeld_id);
    //alert(document.getElementById(Inputfeld_id));
    var slogan0 = document.getElementById(Inputfeld_id).value.toLocaleLowerCase();
    var slogan1 = document.getElementById(Inputfeld_id).value.toLocaleLowerCase();
    var slogan2 = document.getElementById(Inputfeld_id).value.toLocaleLowerCase();
    var slogan3 = document.getElementById(Inputfeld_id).value.toLocaleLowerCase();
    var slogan4 = document.getElementById(Inputfeld_id).value.toLocaleLowerCase();
    var list = list.split(";");
    var list_safe = list_safe.split(";");
    var matches = new Array();
    var y = 0;
    for (var i = 0; i < list.length; i++) {
        var find = list[i].indexOf(slogan0);
        if (find >= 0) {
            matches[y] = list_safe[i];
            y++;
        }
    }
    /* bei veränderung alte Liste löschen und neue Einfügen*/

    while (document.getElementById(Schlagwortliste_id).getElementsByTagName("option").length > 0) {
        var Knoten = document.getElementById(Schlagwortliste_id).getElementsByTagName("option")[0];
        document.getElementById(Schlagwortliste_id).removeChild(Knoten);
    }

    /**bitte auswählen als value 0 einfügen damit onchange bei Set_Value() greift**/
    var Auswahlliste = document.getElementsByName(Schlagwortliste_id)[0];
    var Eintrag = document.createElement("option");
    Eintrag.text = "--bitte ausw\u00e4hlen--";
    Eintrag.value = 0;
    //Auswahlliste.add(Eintrag);
    Auswahlliste = document.getElementById(Schlagwortliste_id);
    Auswahlliste.options[0] = new Option('--bitte ausw\u00e4hlen--', '0');
    /********************************************************************************/


    var Wert = 1;
    for (var i = 0; i < matches.length; i++) {
        var Auswahlliste = document.getElementsByName(Schlagwortliste_id)[0];
        //alert(Schlagwortliste_id);
        var Eintrag = document.createElement("option");
        var FolgendeOption = null;
        Eintrag.text = matches[i];
        Eintrag.value = Wert;
        FolgendeOption = Auswahlliste.length;
        //alert("laenge " + FolgendeOption);
        //Auswahlliste.add(Eintrag, FolgendeOption);
        var Auswahlliste = document.getElementById(Schlagwortliste_id);
        //alert(matches[FolgendeOption-1]);
        Auswahlliste.options[FolgendeOption] = new Option(matches[i], FolgendeOption);
        //alert(Auswahlliste);
        Wert += 1;
    }
}

/*************auswahl abfangen um den wert in inputfeld zu setzen (onclick)********************/
/*																							*/
/********************************************************************************************/
function set_value(index, id_Liste, id_Input, div_tag_id) {

    var select = document.getElementById(id_Liste);
    //alert("idliste " + id_Liste + "index "+ index);
    var neuer_slogan = document.getElementById(id_Liste).getElementsByTagName("option")[index].text;
    document.getElementById(id_Liste).getElementsByTagName("option")[index].setAttribute("selected", "selected");
    document.getElementById(id_Input).value = neuer_slogan;
    document.getElementById(div_tag_id).removeChild(select);
}

function delete_select(id_slogan) {
    var select = document.getElementById(id_Liste);
    document.getElementById(div_tag_id).removeChild(select);
}

function select_erstellen(wohin, id_Liste, id_Input) {
    Schlagwortliste_id = id_Liste
    Inputfeld_id = id_Input
    div_tag_id = wohin

    if (document.getElementById(id_Liste) == null) {
        var wohin = document.getElementById(wohin)
        var select = document.createElement("select");
        select.setAttribute("id", id_Liste);
        select.setAttribute("onchange", "set_value(this.value, Schlagwortliste_id,Inputfeld_id, div_tag_id)");
        //select.setAttribute("onclick","alert(this.value)");
        select.setAttribute("name", id_Liste);
        var option = document.createElement("option");
        option.value = "0";
        option.text = "--bitte ausw\u00e4hlen--";
        select.appendChild(option);
        wohin.appendChild(select);
    }
}
/*******************************************************************************/

function show_constraints(check) {

    if (check == "on") {
        alert("Es wird eine Sicherungskopie erstellt\n Achtung folgende Daten m" + unescape("%FC") + "ssen nicht eingegeben werden \n Autor \n Mediennummer \n ISBN \n Verlag \n Erscheinungsort \n Erscheinungsjahr");
    }
}


/*******************************zeigt signatur**im div sig_start******************************/

function listSignature(signatur, id) {

    var devider = ".";
    var lastPoint = ".";
    var Zusammensetzung = signatur.split(".");
    var Hauptkategorie = Zusammensetzung[0];
    //alert(Zusammensetzung[0]);

    var Systematik = Hauptkategorie.concat(devider + signatur + lastPoint);
    //alert(Systematik);

    if (signatur != 0) {
        Node = document.getElementById(id);
        Node.innerHTML = '';
        wert = document.createTextNode(Systematik);
        document.getElementById(id).appendChild(wert);
    }
    else {
        Node = document.getElementById(id);
        Node.innerHTML = '';
    }


}

function pastValue(woher_id, wohin_id) {
    //alert(woher_id);
    value = document.getElementById(woher_id).getElementsByTagName('option')[document.getElementById(woher_id).selectedIndex].value
    //alert(value);
    if (value != 0) {
        Node = document.getElementById(wohin_id);
        Node.value = value;
    }
    else {
        Node = document.getElementById(wohin_id);
        Node.value = '';
    }


}
/*******************get Value*************from inputfeld*********************************/
function getValue(woher_id, id_Liste) {
    alert(woher_id);
    value = document.getElementById(woher_id).text;
    alert(value);
    setSelect(id_Liste, value);
}
/***********************************Selcect = selectes***********************/

function setSelect(id_Liste, index) {
    alert(id_Liste);
    var wohin = document.getElementById(id_Liste).getElementsByTagName('option')[index];
    var select = document.createElement("select");
    wohin.appendChild(select);
    wohin.select.getElementsByTagName("option")[index].setAttribute("selected", "selected");
}

/******************************zeigt sub category der Signatur ****************/



function listGenreSub(main_category) {
    alert(main_category);
}

/*var myH1 = document.createElement("h1");
 var myText = document.createTextNode("Eine sehr dynamische Seite");
 myH1.appendChild(myText);
 var Ausgabebereich = document.getElementById("Bereich");
 Ausgabebereich.appendChild(myH1);*/

/****************************kennzeichnung der optionalen einträge bei nicht lizensierter Kopie ";) **********************/

function showOptional() {
    //if(secure_copy=="on")
    //{
    //var text = "(optional)";
    //var test = getElementById("isbn_text").innerText;
    //alert(test);
    //}


}		
		

