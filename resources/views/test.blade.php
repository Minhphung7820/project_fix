<div>
  @foreach ($breadcrumbs as $breadcrumb)
  @if (!$loop->last)
  <a href="">{{ $breadcrumb['name'] }}</a> /
  @else
  {{ $breadcrumb['name'] }}
  @endif
  @endforeach
</div>

<h1>{{ $category['name'] }}</h1>
<!-- Other category details go here -->