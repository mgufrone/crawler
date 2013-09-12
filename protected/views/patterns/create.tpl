{% set form = this.beginWidget('CActiveForm',{

}) %}

<div class="form-widget">
	<div class="form-group">
	{{ form.textField(model, 'pattern_name',{placeholder:'Site Name',class:'form-control'}) }}
	</div>
	<div class="form-group">
	{{ form.textField(model, 'pattern_value',{placeholder:'Site URL',class:'form-control'}) }}
	</div>

	<div class="form-group">
		{{ form.dropDownList(model, 'pattern_type', model.getTypes(),{placeholder:'Site URL',class:'form-control'}) }}
	</div>

	<div class="form-group">
	{{ html.submitButton('Submit',{placeholder:'Site URL',class:'btn btn-primary'}) }}
	</div>

</div>
{% do this.endWidget() %}