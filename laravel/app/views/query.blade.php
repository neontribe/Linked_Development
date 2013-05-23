<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">

<html>
<head>
  <title>POC Searches</title>
</head>

<body>

<h1>POC Searches</h1>

{{ Form::open(array('url' => 'query', 'method' => 'GET')) }}

	<h2>Querying {{ $endpoint }}</h2>
	<p>{{ Form::textarea('query', $query) }}</p>
   	<p>{{ Form::submit('Execute') }}</p>

{{ Form::close() }}

@if ( $results )
	<pre>
{{ $results }}
	</pre>
@endif

</body>
</html>
