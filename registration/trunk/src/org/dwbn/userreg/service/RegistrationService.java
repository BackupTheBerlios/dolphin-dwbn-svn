package org.dwbn.userreg.service;

import java.util.List;

import org.dwbn.userreg.model.Registration;


public interface RegistrationService {
    public List<Registration> findAll();

    public void save(Registration registration);

    public void remove(int id);

    public Registration find(int id);
    
    public void changeStatusWaitingToConfirmed(int id);
    
    public void cleanUp();
}
