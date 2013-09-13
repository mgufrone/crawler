<table id="dataTable" class="table-striped table">
	<thead>
		<tr>
			{% for column in columns %}
			<td>{{column}}</td>
			{% endfor %}
		</tr>
	</thead>
	<tbody></tbody>
	<tfoot>
		<tr>
			{% for column in columns %}
			<td>{{column}}</td>
			{% endfor %}
		</tr>
	</tfoot>
</table>