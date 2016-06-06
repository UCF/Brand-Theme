angular.module('UIDSearch', []);
angular.module('UIDSearch').service('UIDSearchService', UIDSearchService);
angular.module('UIDSearch').controller('UIDSearchController', UIDSearchController);

UIDSearchService.$inject = ['$http'];
function UIDSearchService($http) {
    var URL = CONFIG.BASE_URL + "/wp-json/wp/v2/uids?s=";

    var getUids = function (callback, query) {
        $http({
            url: URL,
            method: "GET",
            params: { 's': query }
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
    ctrl.noResults = false;

    function setUids(data) {
        ctrl.loading = false;
        if (data.error === true) {
            ctrl.error = true;
        } else if (data.length === 0) {
            ctrl.noResults = true;
        }
        ctrl.results = data;
        $(".request-form").delay(2000).fadeIn();
    }

    $scope.$watch(angular.bind(ctrl, function () {
        return ctrl.searchQuery.term;
    }), function (newValue) {
        ctrl.noResults = false;
        if (ctrl.searchQuery.term.length > 2) {
            ctrl.error = false;
            ctrl.loading = true;
            UIDSearchService.getUids(setUids, ctrl.searchQuery.term);
        } else {
            ctrl.results = [];
        }
    });

}
