function initDemoForm() {
  $G("range1").addEvent("input", function() {
    $E("register_amount").value = this.value;
  });
  $G("register_amount").addEvent("change", function() {
    $E("range1").setValue(this.value);
  });
  $G("range1").addEvent("change", function() {
    document.title = this.value;
  });
  $G("range2").addEvent("input", function() {
    $E("register_phone").value = this.value;
  });
  $G("range3").addEvent("input", function() {
    $E("register_amount").value = this.value;
  });
  $G("range3").addEvent("change", function() {
    $E("register_phone").value = this.value;
  });
  $G("range4").addEvent("input", function() {
    $E("register_amount").value = this.value;
  });
  $G("range4").addEvent("change", function() {
    $E("register_phone").value = this.value;
  });
  initCalendarRange("register_from_date", "register_to_date");
  $G('select_checkbox').addEvent('change', function() {
    var values = this.value;
    forEach($E('register_province').getElementsByTagName('input'), function() {
      this.checked = values.indexOf(this.value) > -1;
    });
  });
  $E('select_checkbox').value = [104, 105, 106];
  forEach($E('register_province').getElementsByTagName('input'), function() {
    callClick(this, function() {
      var chks = [];
      forEach($E('register_province').getElementsByTagName('input'), function() {
        if (this.checked) {
          chks.push(this.value);
        }
      });
      $E('select_checkbox').value = chks;
    });
  });
  forEach($E('register_permission').getElementsByTagName('input'), function() {
    callClick(this, function() {
      $E(this.value).disabled = !this.checked;
    });
  });
}

function initProvince() {
  new GMultiSelect(["provinceID", "amphurID", "districtID"], {
    action: WEB_URL + "index.php/demo/model/province/get"
  });
}

function initDemoAutocomplete() {
  var o = {
    callBack: function() {
      $G("search_districtID").valid().value = this.district;
      $G("search_amphurID").valid().value = this.amphur;
      $G("search_provinceID").valid().value = this.province;
      $G("search_zipcode").valid().value = this.zipcode;
      $E("districtID").value = this.districtID;
      $E("amphurID").value = this.amphurID;
      $E("provinceID").value = this.provinceID;
    },
    onChanged: function() {
      $G("search_districtID").reset();
      $G("search_amphurID").reset();
      $G("search_provinceID").reset();
      $G("search_zipcode").reset();
      $E("districtID").value = 0;
      $E("amphurID").value = 0;
      $E("provinceID").value = 0;
    }
  };
  initAutoComplete(
    "search_districtID",
    WEB_URL + "index.php/demo/model/autocomplete/district",
    "district,amphur,province,zipcode",
    "location",
    o
  );
  initAutoComplete(
    "search_amphurID",
    WEB_URL + "index.php/demo/model/autocomplete/amphur",
    "district,amphur,province,zipcode",
    "location",
    o
  );
  initAutoComplete(
    "search_provinceID",
    WEB_URL + "index.php/demo/model/autocomplete/province",
    "district,amphur,province,zipcode",
    "location",
    o
  );
  initAutoComplete(
    "search_zipcode",
    WEB_URL + "index.php/demo/model/autocomplete/zipcode",
    "district,amphur,province,zipcode",
    "location",
    o
  );
}
var doEventClick = function(d) {
  alert("id=" + this.id + "\nparams=" + d);
};