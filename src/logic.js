import { menuBuildChild, menuBuildRoot, menuSetRoot, menuInflate } from './menu.js';

export const homeMenu = menuBuildRoot('pages/main.php', 'Accueil');
export const seeMaintenanceFromMenu = menuBuildChild(homeMenu, 'pages/maintenance_details.php', 'Maintenance');
export const updateMaintenanceFromMenu = menuBuildChild(homeMenu, 'pages/maintenance_update.php', 'Modification de la maintenance');

export const clientsMenu = menuBuildRoot('pages/clients.php', 'Clients');
export const clientAdd = menuBuildChild(clientsMenu, 'pages/add_client.php', 'Nouveau client');
export const clientDetails = menuBuildChild(clientsMenu, 'pages/client_details.php', 'Détails du client');
export const clientUpdate = menuBuildChild(clientsMenu, 'pages/client_update.php', 'Modification des informations du client');
export const vehicleDetails = menuBuildChild(clientDetails, 'pages/vehicle_details.php', 'Détails du véhicule');
export const vehicleUpdate = menuBuildChild(clientDetails, 'pages/vehicle_update.php', 'Modification des informations du véhicule');
export const vehicleMaintenance = menuBuildChild(vehicleDetails, 'pages/maintenance_details.php', 'Maintenance du véhicule');
export const vehicleUpdateMaintenance = menuBuildChild(vehicleDetails, 'pages/maintenance_update.php', 'Modification de la maintenance');
export const vehicleNewMaintenance = menuBuildChild(vehicleDetails, 'pages/add_maintenance.php', 'Nouvelle maintenance');
export const vehicleExternMaintenance = menuBuildChild(vehicleDetails, 'pages/extern_maintenance_details.php', 'Maintenance externe du véhicule');
export const vehicleUpdateExternMaintenance = menuBuildChild(vehicleDetails, 'pages/extern_maintenance_update.php', 'Modification de la maintenance externe');
export const vehicleNewExternMaintenance = menuBuildChild(vehicleDetails, 'pages/add_extern_maintenance.php', 'Nouvelle maintenance externe');
export const vehicleNewPrescription = menuBuildChild(vehicleDetails, 'pages/add_prescription.php', 'Nouvelle prescription');
export const clientsAddVehicle = menuBuildChild(clientDetails, 'pages/add_vehicle.php', 'Nouveau véhicule');

export const maintenancesMenu = menuBuildRoot('pages/maintenances.php', 'Maintenances');
export const maintenanceUpdate = menuBuildChild(maintenancesMenu, 'pages/maintenance_update.php', 'Modification de la maintenance');
export const seeMaintenance = menuBuildChild(maintenancesMenu, 'pages/maintenance_details.php', 'Maintenance');
export const searchMaintenance = menuBuildChild(maintenancesMenu, 'pages/search_maintenance.php', 'Recherche de maintenance');
export const seeMaintenanceFromSearch = menuBuildChild(searchMaintenance, 'pages/maintenance_details.php', 'Maintenance');
export const maintenancesRecModelAdd = menuBuildChild(maintenancesMenu, 'pages/add_recurrent_maintenance_model.php', 'Ajout d\'une maintenance récurrente sur modèle');
export const maintenancesRecModelTypeAdd = menuBuildChild(maintenancesMenu, 'pages/add_recurrent_maintenance_model_type.php', 'Ajout d\'une maintenance récurrente sur type de modèle');
export const maintenancesRecModelUpdate = menuBuildChild(maintenancesMenu, 'pages/recurrent_maintenance_model_update.php', 'Modification d\'une maintenance récurrente sur modèle');
export const maintenancesRecModelTypeUpdate = menuBuildChild(maintenancesMenu, 'pages/recurrent_maintenance_model_type_update.php', 'Modification d\'une maintenance récurrente sur la famille de modèle');

export const managementMenu = menuBuildRoot('pages/management.php', 'Gestion du garage');
export const stats = menuBuildChild(managementMenu, 'pages/stats.php', 'Statistiques');
export const manageEmployees = menuBuildChild(managementMenu, 'pages/employees.php', 'Gestion des employés');
export const newEmployee = menuBuildChild(manageEmployees, 'pages/add_employee.php', 'Ajout d\'un employé');
export const employeeDetails = menuBuildChild(manageEmployees, 'pages/employee_details.php', 'Détails de l\'employé');
export const employeeUpdate = menuBuildChild(manageEmployees, 'pages/employee_update.php', 'Modification de l\'employé');
export const manageManufacturers = menuBuildChild(managementMenu, 'pages/manufacturers.php', 'Gestion des constructeurs');
export const newManufacturer = menuBuildChild(manageManufacturers, 'pages/add_manufacturer.php', 'Ajout d\'un constructeur');
export const manufacturerDetails = menuBuildChild(manageManufacturers, 'pages/manufacturer_details.php', 'Détails du constructeur');
export const manufacturerUpdate = menuBuildChild(manageManufacturers, 'pages/manufacturer_update.php', 'Modification du constructeur');
export const modelUpdate = menuBuildChild(manufacturerDetails, 'pages/model_update.php', 'Modification du modèle');
export const newModel = menuBuildChild(manufacturerDetails, 'pages/add_model.php', 'Ajout d\'un modèle');
export const managePartTypes = menuBuildChild(managementMenu, 'pages/part_types.php', 'Gestion des types de pièces');
export const newPartType = menuBuildChild(managePartTypes, 'pages/add_part_type.php', 'Ajout d\'un type de pièce');
export const partTypeUpdate = menuBuildChild(managePartTypes, 'pages/part_type_update.php', 'Modification du type de pièce');
export const manageParts = menuBuildChild(managePartTypes, 'pages/parts.php', 'Gestion des pièces');
export const newPart = menuBuildChild(manageParts, 'pages/add_part.php', 'Ajout d\'une pièce');
export const partUpdate = menuBuildChild(manageParts, 'pages/part_update.php', 'Modification de la pièce');

export const manageModelTypes = menuBuildChild(managementMenu, 'pages/model_types.php', 'Gestion des types de modèle');
export const newModelType = menuBuildChild(manageModelTypes, 'pages/add_model_type.php', 'Ajout de type de modèle');
export const modelTypeUpdate = menuBuildChild(manageModelTypes, 'pages/model_type_update.php', "Modification du type de modèle");

export const interventionsMenu = menuBuildChild(managementMenu, 'pages/interventions.php', 'Interventions');
export const interventionDetails = menuBuildChild(interventionsMenu, 'pages/intervention_details.php', 'Détails de l\'intervention');
export const interventionUpdate = menuBuildChild(interventionsMenu, 'pages/intervention_update.php', 'Modification de l\'intervention');
export const newIntervention = menuBuildChild(interventionsMenu, 'pages/add_intervention.php', 'Nouvelle intervention');

menuInflate(homeMenu);

document.getElementById('logo').addEventListener('click', () => menuSetRoot(homeMenu));
