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
        }).
            error(function (data, status, headers, config) {
                callback({ "error": true });
            });
    };

    return {
        getUids: getUids
    };
}

UIDSearchController.$inject = ['$scope', 'UIDSearchService'];
function UIDSearchController($scope, UIDSearchService) {
    var ctrl = this,
        creds = {
            bucket: 'web.ucf.edu/uid',
            access_key: 'AKIAJELYLJY2FL3ETG4Q',
            secret_key: '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'
        };

    ctrl.searchQuery = { term: '' };
    ctrl.results = [];

    function setUids(data) {
        ctrl.results = data;
        console.log(ctrl.results);
    }

    $scope.$watch(angular.bind(ctrl, function () {
        return ctrl.searchQuery.term;
    }), function (newValue) {
        if (newValue !== '') {
            UIDSearchService.getUids(setUids, newValue);
        }
    });

}
