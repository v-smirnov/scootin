package application

import "github.com/v-smirnov/scootin/app/domain"

type Vehicle struct {
	VehicleIdentifier string             `json:"identifier"`
	VehicleType       domain.VehicleType `json:"type"`
}

type GetVehiclesResponse struct {
	Vehicles []Vehicle `json:"vehicles"`
}
