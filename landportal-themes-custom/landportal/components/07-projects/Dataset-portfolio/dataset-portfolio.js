/*Add Active Class On Dataset Portfolio's nodes <li>*/
jQuery(".new_page_node_dataset li.landportal-menu__item--data").addClass(
  "landportal-menu__item--active"
);

jQuery(
  ".dataset-portfolio .dataset-accordian > div > div .primary-underline"
).click(function () {
  var $content = jQuery(this).parent().children(":not(.primary-underline)");
  $content.toggleClass("hidden");
  $content.slideToggle();
  jQuery(this).toggleClass("open");
});

/*Hide Partners & Donor header if empty*/
var datasetPartner = jQuery(
  ".dataset-portfolio .section__sidebar__aside .dataset-porfolio-partner .views-field-field-orgref .field-content"
).text();
if (datasetPartner == "") {
  jQuery(
    ".dataset-portfolio .section__sidebar__aside .dataset-porfolio-partner"
  ).css("display", "none");
}

var datasetDonors = jQuery(
  ".dataset-portfolio .section__sidebar__aside .dataset-porfolio-donor .views-row"
).text();
if (datasetDonors == "") {
  jQuery(
    ".dataset-portfolio .section__sidebar__aside .dataset-porfolio-donor"
  ).css("display", "none");
}

/*When banner is not available then add class*/
var datasetImage = jQuery(
  ".dataset .layout--onecol .layout--image-full img"
).attr("src");
if (datasetImage == undefined) {
  jQuery(".dataset-portfolio").addClass("no-banner");
}

// Jump SelectList for Dataset Portfolio

var startopt = "";
var navigate_select = Drupal.t("Choose other indicator from this dataset");

var select =
  '<select id="dataset-jump-menu-ct"><option value="">' +
  navigate_select +
  "</option>";
jQuery(
  ".dataset-portfolio-jump-menu-view-ct .views-field-name .field-content a"
).each(function () {
  var datasetText = jQuery(this).text();
  var datasetVal = jQuery(this).attr("href");
  startopt =
    startopt +
    ('<option value="' + datasetVal + '">' + datasetText + "</option>");
});

var selectEnd = "</select>";
var full = select + startopt + selectEnd;
jQuery(".dataset-portfolio-jump-menu-view-ct").append(full);

jQuery("#dataset-jump-menu-ct").on("change", function () {
  var url = jQuery(this).val();
  if (url) {
    window.location = url;
  }
  return false;
});
