<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">  
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-dark">
                    <i class="fas fa-users"></i>
                    Users
                </a>
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-user-plus"></i>
                    New user
                </a>

                
                <a href="{{ route('roles.create') }}" class="btn btn-sm btn-outline-primary float-right ml-1">
                    <i class="fas fa-plus-square"></i>
                    New role
                </a>

                <a href="{{ route('permissions.create') }}" class="btn btn-sm btn-outline-primary float-right ml-1">
                    <i class="fas fa-plus-square"></i>
                    New permission
                </a>

                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-dark float-right">
                    Roles / Permissions
                </a>
            </div>
        </div>
    </div>   
</div>