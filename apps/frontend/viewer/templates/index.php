<script>
publishers = <?=$publishers->toJson()?>;
series = <?=$series->toJson()?>;
issues = <?=$issues->toJson()?>;


function Select(id, params) {
  params.field = params.field || 'name'
  params.target_field = params.target_field || 'name'
  params.def_str = params.def_str || 'Select...'
  params.new_str = params.new_str || 'Create...'

  this.jq = $('#'+id);
  this.jq.change(function(evt) {
    if (evt.target.value == -1) {
      if (params.onCreate instanceof Function) {
        params.onCreate.apply(this);
      }
    } else if (evt.target.value) {
      $.getJSON(params.url+"?"+params.value+"="+evt.target.value+"&jsoncallback=?",
        function(data) {
          Select.fill($('#'+params.target_id).get(0), data, params.target_field, params.def_str, params.new_str);
      });
    }
  })
  this.select = this.jq.get(0);
  this.params = params;
}
Select.prototype.fill = function(items) {
  Select.fill(this.select, items, this.params.field, this.params.def_str, this.params.new_str)
}
Select.fill = function (el, items, field, def_str, new_str) {
  field = field || 'name';
  el.options.length = 0;
  if (def_str)
    el.options[0] = new Option(def_str, '');
  for (var pk in items) {
    var label = (field instanceof Function) ? field.call(this, items[pk]) : items[pk][field];
    el.options[el.options.length] = new Option(label, pk);
  }
  if (new_str)
    el.options[el.options.length] = new Option(new_str, -1);
}

$(document).ready(function() {
  var select_pub = new Select('issue-dialog-publisher_id', {
    'url': "http://api.comicdb.demo/series/get", 
    'value': "publisher_id", 
    'target_id': 'issue-dialog-series_id',
    'target_field': function(obj) {
      var out = obj.name;
      if (obj.version) out += " ["+obj.version+"]";
      if (obj.start_year) out += " ("+obj.start_year+")";
      return out;
    },
    'onCreate': function() {
      alert('new publishder');
    }
  });
  select_pub.fill(publishers);
})

</script>
<style>
  input.number {
    width: 3em;
  }
</style>


<div class="dialog" id="issue-dialog">
  <div class="dialog-title">Create Issue</div>
  <div class="form-row">
    <label for="issue[publisher_id]">Publisher:</label>
    <select id="issue-dialog-publisher_id" name="issue[publisher_id]"></select>
  </div>
  <div class="form-row">
    <label for="issue[series_id]">Series:</label>
    <select id="issue-dialog-series_id" name="issue[series_id]"></select>
  </div>
  <div class="form-row">
    <label for="issue[issue_no]">Issue #:</label>
    <input id="issue-dialog-issue_no" name="issue[issue_no]" class="number"/>
  </div>
  <div class="form-row">
    <label for="issue[story_arc]">Story Arc:</label>
    <input id="issue-dialog-story_arc" name="issue[story_arc]"/>
    #<input id="issue-dialog-arc_no" name="issue[arc_no]" class="number"/>
    / #<input id="issue-dialog-arc_no_max" name="issue[arc_no_max]" class="number"/>
  </div>
  <div class="form-row">
    <label for="issue[print_date]">Print Date:</label>
    <input id="issue-dialog-print_date" name="issue[print_date]"/>
  </div>
  <div class="form-row">
    <label for="issue[print_run]">Print Run:</label>
    <input id="issue-dialog-print_run" name="issue[print_run]" class="number"/>
  </div>
  <div class="form-row">
    <label for="issue[cover]">Cover Style:</label>
    <input id="issue-dialog-cover" name="issue[cover]"/>
  </div>
  <input type="button" value="Save Issue"/>
</div>
