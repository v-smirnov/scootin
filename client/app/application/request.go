package application

import "github.com/v-smirnov/scootin/app/domain"

type UpdateVehicleLocationRequest struct {
	VehicleIdentifier string  `json:"-"`
	Latitude          float32 `json:"latitude"`
	Longitude         float32 `json:"longitude"`
	ReceivedAt        string  `json:"received_at"`
}

type UpdateVehicleStatusRequest struct {
	VehicleIdentifier string               `json:"-"`
	VehicleStatus     domain.VehicleStatus `json:"-"`
	UpdatedAt         string               `json:"updated_at"`
}

type GetVehiclesRequest struct {
	VehicleType    domain.VehicleType   `json:"-"`
	VehicleStatus  domain.VehicleStatus `json:"-"`
	StartLatitude  float32              `json:"start_latitude"`
	StartLongitude float32              `json:"start_longitude"`
	EndLatitude    float32              `json:"end_latitude"`
	EndLongitude   float32              `json:"end_longitude"`
}

func NewUpdateVehicleLocationRequest(
	vehicleIdentifier string,
	latitude float32,
	longitude float32,
	receivedAt string,
) UpdateVehicleLocationRequest {
	return UpdateVehicleLocationRequest{
		VehicleIdentifier: vehicleIdentifier,
		Latitude:          latitude,
		Longitude:         longitude,
		ReceivedAt:        receivedAt,
	}
}

func NewUpdateVehicleStatusRequest(
	vehicleIdentifier string,
	vehicleStatus domain.VehicleStatus,
	updatedAt string,
) UpdateVehicleStatusRequest {
	return UpdateVehicleStatusRequest{
		VehicleIdentifier: vehicleIdentifier,
		VehicleStatus:     vehicleStatus,
		UpdatedAt:         updatedAt,
	}
}

func NewGetVehiclesRequest(
	vehicleType domain.VehicleType,
	vehicleStatus domain.VehicleStatus,
	startLatitude float32,
	startLongitude float32,
	endLatitude float32,
	endLongitude float32,
) GetVehiclesRequest {
	return GetVehiclesRequest{
		VehicleType:    vehicleType,
		VehicleStatus:  vehicleStatus,
		StartLatitude:  startLatitude,
		StartLongitude: startLongitude,
		EndLatitude:    endLatitude,
		EndLongitude:   endLongitude,
	}
}
