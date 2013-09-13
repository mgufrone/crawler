<!DOCTYPE html>
<html>
  <head>
    <title>{{this.pageTitle}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <body>
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex5-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Crawler Engine</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex5-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="{{this.path.task.index}}" class="dropdown-toggle" data-toggle="dropdown">Task</a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="{{this.path.task.index}}">Show All Task</a></li>
                <li><a href="{{this.path.task.create}}">Create Task</a></li>
              </ul>
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </nav>
    <div id="wrapper">
    <div class="container">{{content}}</div>
    </div>
  </body>
</html>
