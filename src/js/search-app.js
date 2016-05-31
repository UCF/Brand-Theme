angular.module('UIDSearch', []);
angular.module('UIDSearch').service('UIDSearchService', UIDSearchService);
angular.module('UIDSearch').controller('UIDSearchController', UIDSearchController);

UIDSearchService.$inject = ['$http'];
function UIDSearchService($http) {
    var URL = "/uid/wp-json/wp/v2/uids/?filter[s]=";

    var getUids = function (callback, query) {
        $http({
            url: URL,
            method: "GET",
            params: { 'filter[s]': query }
        }).success(function (data, status, headers, config) {
            callback(data);
        }).error(function (data, status, headers, config) {
            callback({ "error": true });
        });
    };

    return {
        getUids: getUids
    };
}

UIDSearchController.$inject = ['$scope', 'UIDSearchService'];
function UIDSearchController($scope, UIDSearchService) {
    var ctrl = this;

    ctrl.searchQuery = { term: '' };
    ctrl.results = [];
    ctrl.error = false;
    ctrl.loading = false;

    function setUids(data) {
        ctrl.loading = false;
        if (data.error === true) {
            ctrl.error = true;
        } else {
            ctrl.results = data;
        }
    }

    $scope.$watch(angular.bind(ctrl, function () {
        return ctrl.searchQuery.term;
    }), function (newValue) {
        if (newValue.length > 2) {
            ctrl.error = false;
            ctrl.loading = true;
            $(".request-form").delay(2000).fadeIn();
            UIDSearchService.getUids(setUids, newValue);
        }
    });

}
