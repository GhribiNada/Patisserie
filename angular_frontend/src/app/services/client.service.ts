import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Client } from 'app/models/Client';
import { Observable } from 'rxjs';

const API_URL = "http://localhost:8765/clients"

@Injectable({
  providedIn: 'root'
})
export class ClientService {

  constructor(private http:HttpClient) { }

  getAllClients():Observable<Client[]>{
    return this.http.get<Client[]>(API_URL)
  }
}
