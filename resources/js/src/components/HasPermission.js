export const hasPermission = (permissionName) => {
    var _permissions = [];
    if( localStorage.getItem('currentUser'))
    {
        var _cUser = JSON.parse(localStorage.getItem('currentUser'));
        if(_cUser && _cUser.roles && _cUser.roles.length > 0)
        {
            _cUser.roles.forEach(role => {
                _permissions = _permissions.concat(role.permissions);
            });
        }
    }
    
    if (_permissions.filter(function(permission){ return permission.name === permissionName }).length>0) {
        return true
    } else {
        return false
    }
}