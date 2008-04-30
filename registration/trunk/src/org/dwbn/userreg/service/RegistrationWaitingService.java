package org.dwbn.userreg.service;

import java.util.List;

import org.dwbn.userreg.model.RegistrationWaiting;


public interface RegistrationWaitingService {
    public List<RegistrationWaiting> findAllWaiting();

    public void saveWaiting(RegistrationWaiting registration);

    public void removeWaiting(int id);

    public RegistrationWaiting findWaiting(int id);
    
    public void transferWaitingToConfirmed(int id);
    
    public void cleanUp();
}
