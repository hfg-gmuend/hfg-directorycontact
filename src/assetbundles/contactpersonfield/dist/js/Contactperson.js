if (typeof ContactPerson == "undefined") {
  ContactPerson = {};
}

ContactPerson.Field = Garnish.Base.extend({
  $input: null,
  $elementSelect: null,
  $container: null,
  $details: null,
  $spinner: null,

  inputId: null,
  namespace: null,

  init: function(options) {
    options = JSON.parse(options);

    this.inputId = options.id;
    this.namespace = options.name;

    this.$input = $("#" + options.id);
    this.$container = this.$input.parents(".contactperson-wrapper");
    this.$elementSelect = this.$input.data("elementSelect");
    this.$details = $(".contact-detail", this.$container);
    this.$spinner = $(".spinner", this.$container);

    var _this = this;
    this.$elementSelect.on("selectElements", function(e) { _this.getContactDetails(e, _this) });
    this.$elementSelect.on("removeElements", function(e) { _this.hideContactDetails(e, _this) });
  },
  getContactDetails: function(e, _this) {
    const entryId = e.elements[0].id;

    if (entryId) {
      _this.$spinner.removeClass("hidden");

      Craft.postActionRequest(
        'directory-contact/explorer/get-contact',
        {
          entry: entryId,
          id: _this.inputId,
          name: _this.namespace
        },
        $.proxy( function(response, textStatus) {
          _this.$spinner.addClass("hidden");

          if (textStatus == "success") {
            _this.$details.html(response.html);
            _this.$details.show();
          }
        })
      );
    }
  },
  hideContactDetails: function(e, _this) {
    if(!("elements" in e)) {
      _this.$details.hide();
    }
  }
});
